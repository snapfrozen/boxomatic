<?php

/**
 * This is the model class for table "supplier_purchases".
 *
 * The followings are the available columns in table 'supplier_purchases':
 * @property integer $id
 * @property string $order_notes
 * @property boolean $is_confirmed
 *
 * The followings are the available model relations:
 * @property SupplierProducts $supplierProducts
 */
class SupplierPurchase extends BoxomaticActiveRecord
{
	public $supplier_cert_search;
	public $supplier_name_search;
	public $item_name_search;
	//public $total; //For aggregate SQL
	
	const defaultItemPriceMultiplier = 1.6;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SupplierPurchase the static model class
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
		return $this->tablePrefix . 'supplier_purchases';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('supplier_id, id', 'numerical', 'integerOnly'=>true),
            array('other_costs','numerical'),
			array('order_notes', 'safe'),
            array('is_confirmed', 'boolean'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('supplier_id, supplier_name_search, item_name_search, supplier_cert_search, id, order_notes', 'safe', 'on'=>'search'),
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
            'Products' => array(self::HAS_MANY, 'SupplierPurchaseProduct', 'supplier_purchase_id'),
            'Supplier' => array(self::BELONGS_TO, 'Supplier', 'supplier_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Purchase ID',
			'delivery_date' => 'Delivery Date',
			'order_notes' => 'Order Notes',
			'supplier_cert_search'=>'Supplier Certification',
			'supplier_name_search'=>'Supplier Name',
			'item_name_search'=>'Item',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('delivery_date',$this->delivery_date,true);
		$criteria->compare('order_notes',$this->order_notes,true);
		
		$criteria->compare('certification_status',$this->supplier_cert_search,true);
		$criteria->compare('name',$this->supplier_name_search,true);
		$criteria->compare('item_name',$this->item_name_search,true);
		//$criteria->with = array('supplierProduct'=>array('with'=>'Supplier'));

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>array(
					'delivery_date'=>true	
			))
		));
	}
	
	public function getDelivery_date_formatted()
	{
		return SnapFormat::date($this->delivery_date);
	}
    
    public function getTotal()
    {
        $total=0;
        foreach($this->Products as $SPP) {
            $total += $SPP->price;
        }
        $total += $this->other_costs;
        return $total;
    }
	
	/**
	 * 
	 * @param SupplierPurchase $SP
	 * @return float price
	 */
    /*
	public function getWholesale_price()
	{
		$quantity = (float) $this->delivered_quantity;
		if($quantity)
			return round($this->final_price / $quantity);
		else 
			return 0;
	}
     */
	
	/**
	 * @param SupplierPurchase $supplierPurchase
	 * @return float price
	 */
    /*
	public function getDefaultItemPrice()
	{
		return round($this->getWholesalePrice() * self::defaultItemPriceMultiplier, 2);
	}
	*/
}