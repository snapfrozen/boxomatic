<?php

/**
 * This is the model class for table "customers".
 *
 * The followings are the available columns in table 'customers':
 * @property integer $customer_id
 * @property string $customer_name
 * @property string $customer_phone
 * @property string $customer_mobile
 * @property string $customer_address
 * @property string $customer_address2
 * @property integer $location_id
 * @property string $customer_notes
 * @property string $customer_email
 * @property string $customer_password
 *
 * The followings are the available model relations:
 * @property Locations $location
 */
class Customer extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Customer the static model class
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
		return 'customers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('location_id', 'numerical', 'integerOnly'=>true),
			array('customer_name, customer_phone, customer_mobile, customer_address, customer_address2, customer_notes, customer_email, customer_password', 'length', 'max'=>45),
			array('customer_suburb', 'length', 'max'=>100),
			array('customer_state', 'length', 'max'=>50),
			array('customer_postcode', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('customer_id, customer_name, customer_phone, customer_mobile, customer_address, customer_address2, location_id, customer_notes, customer_email, customer_password', 'safe', 'on'=>'search'),
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
			'location' => array(self::BELONGS_TO, 'Locations', 'location_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'customer_id' => 'Customer',
			'customer_name' => 'Customer Name',
			'customer_phone' => 'Customer Phone',
			'customer_mobile' => 'Customer Mobile',
			'customer_address' => 'Customer Address',
			'customer_address2' => 'Customer Address2',
			'customer_suburb' => 'Suburb',
			'customer_state' => 'State',
			'customer_postcode' => 'Post code',
			'location_id' => 'Pickup Location',
			'customer_notes' => 'Customer Notes',
			'customer_email' => 'Customer Email',
			'customer_password' => 'Customer Password',
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

		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('customer_name',$this->customer_name,true);
		$criteria->compare('customer_phone',$this->customer_phone,true);
		$criteria->compare('customer_mobile',$this->customer_mobile,true);
		$criteria->compare('customer_address',$this->customer_address,true);
		$criteria->compare('customer_address2',$this->customer_address2,true);
		$criteria->compare('customer_suburb',$this->customer_suburb,true);
		$criteria->compare('customer_state',$this->customer_state,true);
		$criteria->compare('customer_postcode',$this->customer_postcode,true);
		$criteria->compare('location_id',$this->location_id);
		$criteria->compare('customer_notes',$this->customer_notes,true);
		$criteria->compare('customer_email',$this->customer_email,true);
		$criteria->compare('customer_password',$this->customer_password,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}