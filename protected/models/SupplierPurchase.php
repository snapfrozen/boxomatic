<?php

/**
 * This is the model class for table "supplier_purchases".
 *
 * The followings are the available columns in table 'supplier_purchases':
 * @property integer $id
 * @property integer $supplier_product_id
 * @property string $propsed_quantity
 * @property string $propsed_price
 * @property string $proposed_delivery_date
 * @property string $order_notes
 * @property string $delivered_quantity
 * @property string $final_price
 * @property string $delivery_notes
 *
 * The followings are the available model relations:
 * @property SupplierProducts $supplierProducts
 */
class SupplierPurchase extends CActiveRecord
{
	public $supplier_cert_search;
	public $supplier_name_search;
	public $item_name_search;
	public $total; //For aggregate SQL
	
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
		return 'supplier_purchases';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, supplier_product_id', 'numerical', 'integerOnly'=>true),
			array('propsed_quantity, propsed_price, delivered_quantity, final_price', 'length', 'max'=>7),
			array('proposed_delivery_date, delivery_date, order_notes, delivery_notes', 'safe'),
			array('item_sales_price','numerical'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('supplier_name_search, item_name_search, supplier_cert_search, id, supplier_product_id, propsed_quantity, propsed_price, proposed_delivery_date, order_notes, delivered_quantity, final_price, delivery_notes', 'safe', 'on'=>'search'),
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
			'supplierProduct' => array(self::BELONGS_TO, 'SupplierProduct', 'supplier_product_id'),
			'inventory' => array(self::HAS_ONE, 'Inventory', 'supplier_purchase_id')
		);
	}
	
	public function beforeSave()
	{
		if(empty($this->item_sales_price)) {
			$this->item_sales_price = $this->wholesale_price * self::defaultItemPriceMultiplier;
		}
		return parent::beforeSave();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Order ID',
			'supplier_product_id' => 'Supplier Product',
			'propsed_quantity' => 'Proposed Quantity',
			'propsed_price' => 'Proposed Price',
			'proposed_delivery_date' => 'Proposed Delivery Date',
			'delivery_date' => 'Delivery Date',
			'order_notes' => 'Order Notes',
			'delivered_quantity' => 'Delivered Quantity',
			'final_price' => 'Final Price',
			'delivery_notes' => 'Delivery Notes',
			'supplier_cert_search'=>'Supplier Certification',
			'supplier_name_search'=>'Supplier Name',
			'item_name_search'=>'Item',
			'proposed_delivery_date_formatted' => 'Proposed Delivery Date',
			'delivery_date_formatted' => 'Delivery Date',
			'item_sales_price' => 'Item Sales Price',
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
		$criteria->compare('supplier_product_id',$this->supplier_product_id);
		$criteria->compare('propsed_quantity',$this->propsed_quantity,true);
		$criteria->compare('propsed_price',$this->propsed_price,true);
		$criteria->compare('proposed_delivery_date',$this->proposed_delivery_date,true);
		$criteria->compare('delivery_date',$this->delivery_date,true);
		$criteria->compare('order_notes',$this->order_notes,true);
		$criteria->compare('delivered_quantity',$this->delivered_quantity,true);
		$criteria->compare('final_price',$this->final_price,true);
		$criteria->compare('delivery_notes',$this->delivery_notes,true);
		
		$criteria->compare('certification_status',$this->supplier_cert_search,true);
		$criteria->compare('name',$this->supplier_name_search,true);
		$criteria->compare('item_name',$this->item_name_search,true);
		$criteria->with = array('supplierProduct'=>array('with'=>'Supplier'));

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>array(
					'proposed_delivery_date'=>true	
			))
		));
	}
	
	public function getProposed_delivery_date_formatted()
	{
		return Yii::app()->snapFormat->date($this->proposed_delivery_date);
	}
	
	public function getDelivery_date_formatted()
	{
		return Yii::app()->snapFormat->date($this->delivery_date);
	}
	
	/**
	 * 
	 * @param SupplierPurchase $SP
	 * @return float price
	 */
	public function getWholesale_price()
	{
		$quantity = (float) $this->delivered_quantity;
		if($quantity)
			return round($this->final_price / $quantity);
		else 
			return 0;
	}
	
	/**
	 * @param SupplierPurchase $supplierPurchase
	 * @return float price
	 */
	public function getDefaultItemPrice()
	{
		return round($this->getWholesalePrice() * self::defaultItemPriceMultiplier, 2);
	}
	
}