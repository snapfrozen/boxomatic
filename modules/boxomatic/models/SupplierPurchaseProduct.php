<?php

/**
 * This is the model class for table "{{supplier_purchase_products}}".
 *
 * The followings are the available columns in table '{{supplier_purchase_products}}':
 * @property integer $id
 * @property integer $supplier_product_id
 * @property integer $supplier_purchase_id
 * @property string $quantity
 * @property string $price
 */
class SupplierPurchaseProduct extends BoxomaticActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return $this->tablePrefix.'supplier_purchase_products';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('supplier_product_id, supplier_purchase_id', 'numerical', 'integerOnly'=>true),
            array('supplier_product_id, supplier_purchase_id', 'required'),
			array('quantity, price', 'length', 'max'=>7),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, supplier_product_id, supplier_purchase_id, quantity, price', 'safe', 'on'=>'search'),
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
            'SupplierPurchase' => array(self::BELONGS_TO, 'SupplierPurchase', 'supplier_purchase_id'),
            'Product' => array(self::BELONGS_TO, 'SupplierProduct', 'supplier_product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'supplier_product_id' => 'Product',
			'supplier_purchase_id' => 'Supplier Purchase',
			'quantity' => 'Quantity',
			'price' => 'Price',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('supplier_product_id',$this->supplier_product_id);
		$criteria->compare('supplier_purchase_id',$this->supplier_purchase_id);
		$criteria->compare('quantity',$this->quantity,true);
		$criteria->compare('price',$this->price,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SupplierPurchaseProduct the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
