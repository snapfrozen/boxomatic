<?php

/**
 * This is the model class for table "customer_delivery_date_items".
 *
 * The followings are the available columns in table 'customer_delivery_date_items':
 * @property integer $id
 * @property integer $customer_delivery_date_id
 * @property integer $supplier_product_id
 * @property string $quantity
 * @property string $price
 * @property string $created
 * @property string $updated
 *
 * The followings are the available model relations:
 * @property CustomerDeliveryDates $customerDeliveryDate
 * @property SupplierProducts $supplierProduct
 */
class CustomerDeliveryDateItem extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'customer_delivery_date_items';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, supplier_product_id', 'required'),
			array('id, customer_delivery_date_id, supplier_product_id', 'numerical', 'integerOnly'=>true),
			array('quantity, price', 'length', 'max'=>7),
			array('created, updated', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_delivery_date_id, supplier_product_id, quantity, price, created, updated', 'safe', 'on'=>'search'),
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
			'deliveryDate' => array(self::BELONGS_TO, 'CustomerDeliveryDate', 'customer_delivery_date_id'),
			'products' => array(self::BELONGS_TO, 'SupplierProduct', 'supplier_product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'customer_delivery_date_id' => 'Customer Delivery Date',
			'supplier_product_id' => 'Supplier Product',
			'quantity' => 'Quantity',
			'price' => 'Price',
			'created' => 'Created',
			'updated' => 'Updated',
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
		$criteria->compare('customer_delivery_date_id',$this->customer_delivery_date_id);
		$criteria->compare('supplier_product_id',$this->supplier_product_id);
		$criteria->compare('quantity',$this->quantity,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('updated',$this->updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CustomerDeliveryDateItem the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
