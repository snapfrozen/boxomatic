<?php

/**
 * This is the model class for table "supplier_products".
 *
 * The followings are the available columns in table 'supplier_products':
 * @property integer $id
 * @property integer $supplier_id
 * @property integer $packing_station_id
 * @property string $name
 * @property string $value
 * @property string $unit
 * @property string $available_from
 * @property string $available_to
 * @property string $price
 * @property string $wholesale_price
 * @property string $quantity_options
 *
 * The followings are the available model relations:
 * @property Supplier $supplier
 */
class SupplierProduct extends BoxomaticActiveRecord
{
	static $imageDir = 'supplierproduct';
	static $defaultImageLocation = 'products/default.gif';
	
    /*
     * supplier name search attribute
     */
    public $supplier_search;
	public $in_inventory;
	
	/*
	 * attribute for searching availablity by month
	 */
	public $month_available;
	
	public function behaviors()
	{
		return array(
			'activerecord-relation'=>array(
				'class'=>'boxomatic.extensions.active-relation-behavior.EActiveRecordRelationBehavior',
			)
		);
	}
    
    
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
		return $this->tablePrefix . 'supplier_products';
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
			array('packing_station_id, supplier_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>45, 'min'=>3),
			array('value', 'length', 'max'=>7),
			array('unit', 'length', 'max'=>5),
			array('limited_stock', 'boolean'),
			array('description, available_from, available_to', 'safe'),
			array('image_ext', 'length', 'max'=>20),
			array('quantity_options', 'length', 'max'=>255),
			array('image', 'file', 'types'=>'jpg, gif, png', 'allowEmpty'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, in_inventory, supplier_id, supplier_search, month_available, name, value, unit, available_from, available_to', 'safe', 'on'=>'search'),
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
			'Categories' => array(self::MANY_MANY, 'Category', SnapUtil::config('boxomatic/tablePrefix').'supplier_product_categories(supplier_product_id, category_id)'),
			'PackingStation' => array(self::BELONGS_TO, 'PackingStation', 'packing_station_id'),
			'Inventory' => array(self::HAS_MANY, 'Inventory', 'supplier_product_id'),
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
			'available_from' => 'Available From',
			'available_to' => 'Available To',
			'supplier_search' => 'Supplier Name',
			'month_available' => 'Month Available',
			'limited_stock' => 'Limited Stock',
			'image' => 'Image',
			'packing_station_id' => 'Packing Station',
			'quantity_options' => 'Quantity Options',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($DeliveryDate=null)
	{
		$pageSize=isset($_GET['pageSize'])?$_GET['pageSize']:10;
		Yii::app()->user->setState('pageSize',$pageSize);
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		
		if(empty($this->month_available) && $DeliveryDate) {
			$month = date('n',strtotime($DeliveryDate->date));;
			$this->month_available = $month;
		}
		
			
		$criteria=new CDbCriteria;
        $criteria->with = array('Supplier');
		
		if($this->in_inventory === "1") 
		{
			//FB - Breaks! :( http://www.yiiframework.com/forum/index.php/topic/10533-inner-join-with-complex-on-clause/
			//$criteria->with []= 'Inventory';
			//$criteria->addCondition('Inventory.inventory_id IS NOT NULL');
			
			$criteria->join ='
				INNER JOIN Suppliers
				ON ( t.supplier_id = Suppliers.id)
				INNER JOIN Inventory
				ON ( t.id = Inventory.supplier_product_id)
			';
		}
		else if($this->in_inventory === "0")
		{
			$criteria->join ='
				INNER JOIN Suppliers
				ON ( t.supplier_id = Suppliers.id)
				LEFT JOIN Inventory
				ON ( t.id = Inventory.supplier_product_id)
			';
			$criteria->addCondition('Inventory.inventory_id IS null');
		}
		
		$criteria->compare('id',$this->id);
		$criteria->compare('supplier_id',$this->supplier_id);
		$criteria->compare('Supplier.name', $this->supplier_search, true );
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('unit',$this->unit,true);
		
		if($this->month_available) {
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
	
	public static function setPHPThumbParameters($size)
	{
		$_GET += self::$phpThumbSizes[$size];
	}
	
	/**
	 * Returns the human readable unit label for the SupplierProduct
	 */
	public function getunit_label()
	{
		return SnapUtil::config('boxomatic/itemUnits.'.$this->item_unit);
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
	
	public static function getUncategorised()
	{
		$criteria = new CDbCriteria;
		$criteria->join = 'LEFT JOIN supplier_product_categories spc ON t.id = spc.supplier_product_id';
		$criteria->addCondition('category_id IS NULL');
		
		return self::model()->findAll($criteria);
	}
	
	public function beforeSave()
	{	
		$dataDir = Yii::getPathOfAlias('frontend.data');
		
		$field='image';
		$uploadFile=CUploadedFile::getInstance($this,$field);
		
		if(!$uploadFile) 
			return parent::beforeSave();

		$this->$field=$uploadFile;
		$dirPath=$dataDir.'/'.strtolower(__CLASS__);
		if (!file_exists($dirPath)) {
			mkdir($dirPath, 0777, true);
		}

		if(!$this->$field || !$this->$field->saveAs($dirPath.'/'.$field.'_'.$this->id))
			Yii::app()->user->setFlash('danger','problem saving image for field: '.$field);
		
		return parent::beforeSave();
	}
	
	public function getQuantityInput($OrderItem, $form, $name)
	{
		if(!empty($this->quantity_options))
		{
			$op = explode(',',$this->quantity_options);
			$options = array();
			foreach($op as $value) {
				$options[number_format($value,2)] = $value;
			}
			return $form->dropDownList($OrderItem, 'quantity', $options, array('name'=>$name));
		}
		else
		{
			return $form->textField($OrderItem, 'quantity', array('name'=>$name));
		}
	}
}