<?php

/**
 * This is the model class for table "user_payments".
 *
 * The followings are the available columns in table 'user_payments':
 * @property integer $payment_id
 * @property string $payment_value
 * @property string $payment_type
 * @property string $payment_date
 * @property integer $user_id
 */
class UserPayment extends BoxomaticActiveRecord
{
	public $customer_first_name;
	public $customer_last_name;
	public $customer_user_id;
	static public $currency = "AUD";
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserPayment the static model class
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
		return $this->tablePrefix . 'user_payments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('payment_value', 'length', 'max'=>7),
			array('payment_type', 'length', 'max'=>45),
			array('payment_date', 'safe'),
			array('payment_note', 'safe'),
			
			// Only process completed payments
			array('paypal_payment_status', 'compare', 'compareValue'=>'Completed', 'on'=>'PaypalIPN'),
			// Do not process duplicates
			array('paypal_txn_id','unique', 'on'=>'PaypalIPN'),
			// Only process payments sent to configured account
			array('paypal_receiver_email', 'compare', 'compareValue'=>Yii::app()->getModule('payPal')->account->email, 'on'=>'PaypalIPN'),
			// Check currency
			array('paypal_mc_currency', 'compare', 'compareValue'=>self::$currency, 'on'=>'PaypalIPN'),
			// Check payment
			array('paypal_mc_gross', 'paymentValidator', 'amount', 'on'=>'PaypalIPN'),
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('customer_user_id, customer_first_name, customer_last_name, payment_note, payment_id, payment_value, payment_type, payment_date, user_id', 'safe', 'on'=>'search'),
		);
	}
	
	/**
	 * Validate payment.
	 * 
	 * @param string $attribute mc_gross from PayPal
	 * @param array $params
	 */
	public function paymentValidator($attribute, $params) {
		if ($attribute < 0)
			$this->addError($attribute, "Payment must be more than 0");
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'User'=>array(self::BELONGS_TO,'BoxomaticUser','user_id'),
			'Staff'=>array(self::BELONGS_TO,'BoxomaticUser','staff_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'payment_id' => 'Payment',
			'payment_value' => 'Amount',
			'payment_type' => 'Type',
			'payment_date' => 'Date',
			'payment_note' => 'Notes',
			'user_id' => 'Customer',
			'customer_first_name' => 'First Name',
			'customer_last_name' => 'Last Name',
			'customer_user_id' => 'User ID',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$pageSize=isset($_GET['pageSize'])?$_GET['pageSize']:10;
		Yii::app()->user->setState('pageSize',$pageSize);

		$criteria=new CDbCriteria;
		
		$criteria->together=true;
		$criteria->with=array('User');
		if($this->customer_first_name) {
			$criteria->compare('User.first_name',$this->customer_first_name,true);
		}
		if($this->customer_last_name) {
			$criteria->compare('User.last_name',$this->customer_last_name,true);
		}
		if($this->customer_user_id) {
			$criteria->compare('User.last_name',$this->customer_user_id,true);
		}

		$criteria->compare('payment_id',$this->payment_id);
		$criteria->compare('payment_value',$this->payment_value,true);
	    $criteria->compare('payment_note',$this->payment_note,true);
		$criteria->compare('payment_type',$this->payment_type,true);
		$criteria->compare('payment_date',$this->payment_date,true);
		$criteria->compare('user_id',$this->user_id);
		
		
		//$criteria->order='payment_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'payment_id DESC'
			),
			'pagination'=>array(
				'pageSize'=>$pageSize,
			),
		));
	}
	
	/**
	* Only allow admins to access all user information
	*/
	
	//This was messing up the CustbonusCommand email
	/*
	public function defaultScope()
	{
		//This messes up the unique validation :(
		if(!Yii::app()->user->checkAccess('Admin')) 
		{
			return array(
				'condition' => "user_id = '" . Yii::app()->user->user_id . "'",
			);
		}
		else
		{
			return parent::defaultScope();
		}
	}
	 */
}