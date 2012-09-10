<?php

/**
 * This is the model class for table "customer_payments".
 *
 * The followings are the available columns in table 'customer_payments':
 * @property integer $payment_id
 * @property string $payment_value
 * @property string $payment_type
 * @property string $payment_date
 * @property integer $customer_id
 */
class CustomerPayment extends CActiveRecord
{
	public $customer_first_name;
	public $customer_last_name;
	public $customer_box_price;
	public $customer_user_id;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CustomerPayment the static model class
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
		return 'customer_payments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id', 'numerical', 'integerOnly'=>true),
			array('payment_value', 'length', 'max'=>7),
			array('payment_type', 'length', 'max'=>45),
			array('payment_date', 'safe'),
			array('payment_note', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('customer_first_name, customer_last_name, payment_note, payment_id, payment_value, payment_type, payment_date, customer_id', 'safe', 'on'=>'search'),
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
			'Customer'=>array(self::BELONGS_TO,'Customer','customer_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'payment_id' => 'Payment',
			'payment_value' => 'Payment Value',
			'payment_type' => 'Payment Type',
			'payment_date' => 'Payment Date',
			'payment_note' => 'Payment Note',
			'customer_id' => 'Customer',
			'customer_first_name' => 'First Name',
			'customer_last_name' => 'Last Name',
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
		
		$criteria->together=true;
		$criteria->with=array('Customer.User');
		if($this->customer_first_name) {
			$criteria->compare('User.first_name',$this->customer_first_name,true);
		}
		if($this->customer_last_name) {
			$criteria->compare('User.last_name',$this->customer_last_name,true);
		}

		$criteria->compare('payment_id',$this->payment_id);
		$criteria->compare('payment_value',$this->payment_value,true);
	    $criteria->compare('payment_note',$this->payment_note,true);
		$criteria->compare('payment_type',$this->payment_type,true);
		$criteria->compare('payment_date',$this->payment_date,true);
		$criteria->compare('customer_id',$this->customer_id);
		
		$criteria->order='payment_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	* Only allow admins to access all user information
	*/
	public function defaultScope()
	{
		//This messes up the unique validation :(
		if(!Yii::app()->user->checkAccess('admin')) 
		{
			return array(
				'condition' => "customer_id = '" . Yii::app()->user->customer_id . "'",
			);
		}
		else
		{
			return parent::defaultScope();
		}
	}
}