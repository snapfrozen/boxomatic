<?php

/**
 * This is the model class for table "inventory".
 *
 * The followings are the available columns in table 'inventory':
 * @property integer $inventory_id
 * @property integer $supplier_purchase_id
 * @property string $quantity
 * @property string $box_reserve
 */
class Inventory extends CActiveRecord
{
	const defaultItemPriceMultiplier = 1.6;
	public $sum_quantity; //Aggregate variable
	public $sum_box_reserve; //Aggregate variable
	public $supplier_name_search;
	public $product_name_search;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'inventory';
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
			array('supplier_product_id, supplier_purchase_id', 'numerical', 'integerOnly'=>true),
			array('quantity, box_reserve', 'length', 'max'=>7),
			array('notes', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('sum_quantity, sum_box_reserve, inventory_id, supplier_purchase_id, quantity, box_reserve, supplier_name_search, product_name_search', 'safe', 'on'=>'search'),
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
			'quantity' => 'Quantity',
			'box_reserve' => 'Box Reserve',
			'sum_box_reserve' => 'Box Reserve Quantity',
			'sum_quantity' => 'Extras Quantity',
			'total_quantity' => 'Total Quantity',
			'supplier_product_id' => 'Product',
			'product_name_search' => 'Product',
			'supplier_name_search' => 'Supplier',
		);
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
		$criteria=new CDbCriteria;

		$criteria->compare('inventory_id',$this->inventory_id);
		$criteria->compare('supplier_purchase_id',$this->supplier_purchase_id);
		$criteria->compare('quantity',$this->quantity,true);
		$criteria->compare('box_reserve',$this->box_reserve,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function searchIndex()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('inventory_id',$this->inventory_id);
		$criteria->compare('supplier_purchase_id',$this->supplier_purchase_id);
		$criteria->compare('Supplier.name',$this->supplier_name_search,true);
		$criteria->compare('supplierProduct.name',$this->product_name_search,true);
		$criteria->compare('quantity',$this->quantity,true);
		$criteria->compare('box_reserve',$this->box_reserve,true);
		//$criteria->compare('SUM(quantity)',$this->sum_quantity);

		$criteria->with = array('supplierProduct'=>array('with'=>'Supplier'));
		$criteria->group = 'supplier_product_id';
		$criteria->select = '*, SUM(quantity) as sum_quantity, SUM(box_reserve) as sum_box_reserve';

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
		return $this->sum_box_reserve + $this->sum_quantity;
	}
}
