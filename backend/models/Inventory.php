<?php

/**
 * This is the model class for table "inventory".
 *
 * The followings are the available columns in table 'inventory':
 * @property integer $inventory_id
 * @property integer $supplier_purchase_id
 * @property integer $order_item_id
 * @property string $quantity
 */
class Inventory extends BoxomaticActiveRecord
{
	public $sum_quantity; //Aggregate variable
	public $supplier_name_search;
	public $product_name_search;
	
	public $showLimitedStockAt = 20;
	public static $quantityOptions = array(
		'0.00'=>0,
		'1.00'=>1,
		'2.00'=>2,
		'3.00'=>3,
		'4.00'=>4,
		'5.00'=>5,
		'6.00'=>6,
		'7.00'=>7,
		'8.00'=>8,
		'9.00'=>9,
		'10.00'=>10
	);
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return $this->tablePrefix . 'inventory';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('supplier_product_id, quantity', 'required'),
			array('order_item_id, supplier_product_id, supplier_purchase_id', 'numerical', 'integerOnly'=>true),
			array('quantity', 'length', 'max'=>7),
			array('notes', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('sum_quantity, inventory_id, supplier_purchase_id, quantity, supplier_name_search, product_name_search', 'safe', 'on'=>'search'),
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
			'supplierPurchase'=>array(self::BELONGS_TO, 'SupplierPurchase', 'supplier_purchase_id'),
			'supplierProduct'=>array(self::BELONGS_TO, 'SupplierProduct', 'supplier_product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'inventory_id' => 'Inventory',
			'supplier_purchase_id' => 'Grower Purchase',
			'quantity' => 'In Stock',
			'sum_quantity' => 'Quantity',
			'supplier_product_id' => 'Product',
			'product_name_search' => 'Product',
			'supplier_name_search' => 'Supplier',
			'delivery_date_formatted' => 'Delivery Date',
		);
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria;

		$criteria->compare('inventory_id',$this->inventory_id);
		$criteria->compare('supplier_purchase_id',$this->supplier_purchase_id);
		$criteria->compare('quantity',$this->quantity,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function searchIndex()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('inventory_id',$this->inventory_id);
		$criteria->compare('supplier_purchase_id',$this->supplier_purchase_id);
		$criteria->compare('Supplier.name',$this->supplier_name_search,true);
		$criteria->compare('supplierProduct.name',$this->product_name_search,true);
		$criteria->compare('quantity',$this->quantity,true);
		//$criteria->compare('SUM(quantity)',$this->sum_quantity);

		$criteria->with = array('supplierProduct'=>array('with'=>'Supplier'));
		$criteria->group = 'supplier_purchase_id';
		$criteria->select = '*, SUM(quantity) as sum_quantity';
		$criteria->having = 'sum_quantity != 0';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Inventory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getTotal_quantity()
	{
		return $this->sum_quantity;
	}
	
	/**
	 * Get the available items for a particular date
	 * @param type $dateId
	 */
	public function getAvailableItems($dateId, $cat)
	{
		$criteria=new CDbCriteria;
		//$criteria->with = array('supplierProduct'=>array('with'=>'Supplier'));
		$criteria->group = 't.supplier_product_id, supplier_purchase_id';
		$criteria->select = 't.*, SUM(quantity) as sum_quantity';
		$criteria->with = array('supplierPurchase','supplierProduct');
		$prefix=SnapUtil::config('boxomatic/tablePrefix');
		
		if($cat == Category::uncategorisedCategory) {
			$criteria->addCondition('category_id is null');
			$criteria->join = "LEFT JOIN {$prefix}supplier_product_categories spc ON t.supplier_product_id = spc.supplier_product_id";
		} else {
			$criteria->addCondition('category_id=:catId');
			$criteria->join = "INNER JOIN {$prefix}supplier_product_categories spc ON t.supplier_product_id = spc.supplier_product_id";
			$criteria->params = array(':catId'=>$cat);
		}
		$criteria->having = 'limited_stock = 0 OR (limited_stock = 1 && sum_quantity > 0)';
		return $this->findAll($criteria);
	}
	
	public function showQuantity()
	{
		return $this->supplierProduct->limited_stock && $this->sum_quantity < $this->showLimitedStockAt;
	}
	
	public function getDelivery_date_formatted()
	{
		if($this->supplierPurchase)
			return SnapFormat::date($this->delivery_date);
		else
			return '';
	}
}
