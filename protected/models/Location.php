<?php

/**
 * This is the model class for table "locations".
 *
 * The followings are the available columns in table 'locations':
 * @property integer $location_id
 * @property string $location_name
 * @property string $location_delivery_value
 *
 * The followings are the available model relations:
 * @property Customers[] $customers
 */
class Location extends CActiveRecord
{
	public $pickup_label='Pick Up';
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Location the static model class
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
		return 'locations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('location_name', 'length', 'max'=>45),
			array('location_delivery_value', 'length', 'max'=>7),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('location_id, location_name, location_delivery_value', 'safe', 'on'=>'search'),
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
			'customers' => array(self::HAS_MANY, 'Customers', 'location_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'location_id' => 'Location',
			'location_name' => 'Delivery Location',
			'location_delivery_value' => 'Delivery',
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

		$criteria->compare('location_id',$this->location_id);
		$criteria->compare('location_name',$this->location_name,true);
		$criteria->compare('location_delivery_value',$this->location_delivery_value,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		
	public function getLocation_and_delivery()
	{
		return $this->location_name . ' (' . $this->location_delivery_value . ')';
	}
	
	public function getDeliveryList()
	{
		return CHtml::listData($this->findAll('is_pickup=0'),'location_id','location_name');
	}
	
	public function getPickupList()
	{
		return CHtml::listData($this->findAll('is_pickup=1'),'location_id','location_name','pickup_label');
	}
}