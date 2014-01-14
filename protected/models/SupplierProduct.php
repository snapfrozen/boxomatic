<?php

/**
 * This is the model class for table "supplier_products".
 *
 * The followings are the available columns in table 'supplier_products':
 * @property integer $id
 * @property integer $supplier_id
 * @property string $name
 * @property string $value
 * @property string $unit
 * @property string $available_from
 * @property string $available_to
 * @property string $price
 * @property string $wholesale_price
 *
 * The followings are the available model relations:
 * @property Supplier $supplier
 */
class SupplierProduct extends CActiveRecord
{

    /*
     * supplier name search attribute
     */
    public $supplier_search;
	/*
	 * attribute for searching availablity by month
	 */
	public $month_available;
    
    
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SupplierProduct the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'supplier_products';
	}
	
	public function getUnitList()
	{
	    $unit_list = array(
			'KG' => 'Per kg',
	        'EA' => 'Each',
			'BUNCH' => 'Per bunch',
		);
	    return $unit_list;
	}
	
	public function getMonthList()
	{
	    $month_list = array();
	    for($i=1; $i <= 12; $i++)
	    {
	        $month_list[$i] = date('F', strtotime('2000-'.$i.'-05'));
	    }
	    return $month_list;
	}


	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('supplier_id', 'numerical', 'integerOnly'=>true),
			array('price, wholesale_price', 'numerical'),
			array('name', 'length', 'max'=>45),
			array('value', 'length', 'max'=>7),
			array('unit', 'length', 'max'=>5),
			array('available_from, available_to', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, supplier_id, supplier_search, month_available, name, value, unit, available_from, available_to', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'Supplier' => array(self::BELONGS_TO, 'Supplier', 'supplier_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Item',
			'supplier_id' => 'Supplier',
			'name' => 'Name',
			'value' => 'Value',
			'unit' => 'Unit',
			'price' => 'Unit Price',
			'wholesale_price' => 'Unit Wholesale Price',
			'available_from' => 'Available From',
			'available_to' => 'Available To',
			'supplier_search' => 'Supplier Name',
			'month_available' => 'Month Available',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$pageSize=isset($_GET['pageSize'])?$_GET['pageSize']:10;
		Yii::app()->user->setState('pageSize',$pageSize);
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

        $criteria->with = array('Supplier'=>array(
//			'select'=>'IF(id is null, 1, id) as id',
//			'select'=>'2 AS id',
//			'joinType'=>'RIGHT JOIN'
		));
		$criteria->compare('id',$this->id);
		$criteria->compare('supplier_id',$this->supplier_id);
		$criteria->compare('Supplier.name', $this->supplier_search, true );
		$criteria->compare('name',$this->name,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('unit',$this->unit,true);
		
		if($this->month_available)
		{
			$criteria->addCondition($this->month_available . ' BETWEEN available_from AND available_to');
		}

		$criteria->order='Supplier.name';
		$criteria->addCondition('Supplier.status='.Supplier::STATUS_ACTIVE);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>$pageSize,
			),
		));
	}
	
	/**
	 * Returns the human readable unit label for the SupplierProduct
	 */
	public function getunit_label()
	{
		return Yii::app()->params['itemUnits'][$this->unit];
	}
	
	public function getName_with_unit()
	{
		return $this->name . ' (' . $this->unit. ')';
	}
	
	public static function getDropdownListItems($supplierId=null)
	{
		$criteria = new CDbCriteria;
		$criteria->order = 'Supplier.name, t.name';
		if($supplierId) {
			$criteria->addCondition('t.supplier_id='.$supplierId);	
		}
		
		$items = self::model()->with('Supplier')->findAll($criteria);
		return CHtml::listData($items,'id','name_with_unit');
	}
	
	/**
	 * @param SupplierPurchase $supplierPurchase
	 * @return float price
	 */
	public function getDefaultItemPrice($SP)
	{
		return round($this->getWholesalePrice($SP) * self::defaultItemPriceMultiplier,2);
	}
	
	/**
	 * 
	 * @param SupplierPurchase $SP
	 * @return float price
	 */
	public function getWholesalePrice($SP)
	{
		return round($SP->final_price / $SP->delivered_quantity);
	}
}