<?php

/**
 * This is the model class for table "customer_weeks".
 *
 * The followings are the available columns in table 'customer_weeks':
 * @property integer $customer_week_id
 * @property integer $week_id
 * @property integer $customer_id
 * @property integer $customer_location_id
 *
 * The followings are the available model relations:
 * @property Weeks $week
 * @property Customers $customer
 * @property Locations $location
 */
class CustomerWeek extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CustomerWeek the static model class
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
		return 'customer_weeks';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('week_id, customer_id, location_id', 'required'),
			array('week_id, customer_id', 'numerical', 'integerOnly'=>true),
			array('customer_location_id', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('customer_week_id, week_id, customer_id, customer_location_id', 'safe', 'on'=>'search'),
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
			'Week' => array(self::BELONGS_TO, 'Week', 'week_id'),
			'Customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
			'CustomerLocation' => array(self::BELONGS_TO, 'CustomerLocation', 'customer_location_id'),
			'Location' => array(self::BELONGS_TO, 'Location', 'location_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'customer_week_id' => 'Customer Week',
			'week_id' => 'Week',
			'customer_id' => 'Customer',
			'customer_location_id' => 'Customer Location',
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

		$criteria->compare('customer_week_id',$this->customer_week_id);
		$criteria->compare('week_id',$this->week_id);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('customer_location_id',$this->customer_location_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/*
	 * Only allow changes before the delivery deadline if the user is not an admin
	 */
	public function beforeSave()
	{
		$Week=$this->Week;
		
		if(time() > strtotime($Week->deadline) && 
			!Yii::app()->user->checkAccess('admin') && 
			!isset(Yii::app()->user->shadow_id) && 
			Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->shadow_id))
			return false;
		else 
			return true;
	}
	
	public function getLocation_key() 
	{
		if($this->customer_location_id)
			return $this->customer_location_id.'-'.$this->location_id;
		else {
			return $this->location_id;
		}
	}
}