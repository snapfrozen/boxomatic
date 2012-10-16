<?php

/**
 * This is the model class for table "customer_locations".
 *
 * The followings are the available columns in table 'customer_locations':
 * @property integer $customer_location_id
 * @property integer $customer_id
 * @property integer $location_id
 * @property string $address
 * @property string $address2
 * @property string $suburb
 * @property string $state
 * @property string $postcode
 * @property string $phone
 *
 * The followings are the available model relations:
 * @property Customers $customer
 * @property Locations $location
 */
class CustomerLocation extends CActiveRecord
{
	public $delivery_label='Delivery Locations';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CustomerLocation the static model class
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
		return 'customer_locations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_location_id, customer_id, location_id', 'numerical', 'integerOnly'=>true),
			array('address, address2', 'length', 'max'=>150),
			array('suburb, state, postcode, phone', 'length', 'max'=>45),
			array('address, suburb, state, postcode, location_id', 'required'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('customer_location_id, customer_id, location_id, address, address2, suburb, state, postcode, phone', 'safe', 'on'=>'search'),
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
			'Customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
			'Location' => array(self::BELONGS_TO, 'Location', 'location_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'customer_location_id' => 'Location',
			'customer_id' => 'Customer',
			'location_id' => 'Location',
			'address' => 'Address',
			'address2' => 'Address2',
			'suburb' => 'Suburb',
			'state' => 'State',
			'postcode' => 'Postcode',
			'phone' => 'Phone',
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

		$criteria->compare('customer_location_id',$this->customer_location_id);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('location_id',$this->location_id);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('address2',$this->address2,true);
		$criteria->compare('suburb',$this->suburb,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('postcode',$this->postcode,true);
		$criteria->compare('phone',$this->phone,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getLocation_key() {
		return $this->customer_location_id.'-'.$this->location_id;
	}
	public function getFull_location() {
		return $this->address.' - '.$this->Location->location_name;
	}
	
	public function getFull_address()
	{
		$addr=array();
		if(!empty($this->address))
			$addr[]=$this->address;
		if(!empty($this->address2))
			$addr[]=$this->address2;
		if(!empty($this->suburb))
			$addr[]=$this->suburb;
		if(!empty($this->state))
			$addr[]=$this->state;
		if(!empty($this->postcode))
			$addr[]=$this->postcode;
		if(!empty($this->telephone))
			$addr[]='('.$this->telephone.')';
			
		return implode(', ', $addr);
	}
}