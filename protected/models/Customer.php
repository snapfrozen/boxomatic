<?php

/**
 * This is the model class for table "customers".
 *
 * The followings are the available columns in table 'customers':
 * @property integer $customer_id
 * @property integer $location_id
 * @property string $customer_notes
 *
 * The followings are the available model relations:
 * @property CustomerBoxes[] $customerBoxes
 * @property Locations $location
 */
class Customer extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
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
			array('customer_notes', 'length', 'max'=>500),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('customer_id, location_id, customer_notes', 'safe', 'on'=>'search'),
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
			'User' => array(self::HAS_ONE, 'User', 'id'),
			'CustomerBoxes' => array(self::HAS_MANY, 'CustomerBox', 'customer_id'),
			'Boxes' => array(self::MANY_MANY, 'Box', 'customer_boxes(customer_id,box_id)'),
			'Location' => array(self::BELONGS_TO, 'Location', 'location_id'),
			'totalOrders'=>array(
                self::STAT, 'Box', 'customer_boxes(customer_id, box_id)', 
				'select' => 'SUM((box_price * quantity) + (delivery_cost * quantity))',
            ),
			'totalPayments'=>array(
                self::STAT, 'CustomerPayment', 'customer_id', 'select' => 'SUM(payment_value)'
            ),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'customer_id' => 'Customer',
			'location_id' => 'Delivery Location',
			'customer_notes' => 'Customer Notes',
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
		$criteria->compare('location_id',$this->location_id);
		$criteria->compare('customer_notes',$this->customer_notes,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public function getBalance()
	{
		return $this->totalPayments;
	}
	
	public function totalByWeek($weekId)
	{
		$customerId=Yii::app()->user->customer_id;
		
		$criteria=new CDbCriteria;
		$criteria->condition = 'week_id=:weekId AND customer_id=:customerId';
		$criteria->params = array(':weekId'=>$weekId,':customerId'=>$customerId);
		$criteria->select = 'SUM((box_price * quantity) + (delivery_cost * quantity)) as week_total';
		
		$result = CustomerBox::model()->with('Box')->find($criteria);		
		return $result ? $result->week_total : '';
	}
	
	public function totalDeliveryByWeek($weekId)
	{
		$customerId=Yii::app()->user->customer_id;
		
		$criteria=new CDbCriteria;
		$criteria->condition = 'week_id=:weekId AND customer_id=:customerId';
		$criteria->params = array(':weekId'=>$weekId,':customerId'=>$customerId);
		$criteria->select = 'SUM(delivery_cost * quantity) as week_total';
		
		$result = CustomerBox::model()->with('Box')->find($criteria);		
		return $result ? $result->week_total : '';
	}
	
	public function totalBoxesByWeek($weekId)
	{
		$customerId=Yii::app()->user->customer_id;
		
		$criteria=new CDbCriteria;
		$criteria->condition = 'week_id=:weekId AND customer_id=:customerId';
		$criteria->params = array(':weekId'=>$weekId,':customerId'=>$customerId);
		$criteria->select = 'SUM((box_price * quantity)) as week_total';
		
		$result = CustomerBox::model()->with('Box')->find($criteria);		
		return $result ? $result->week_total : '';
	}
	
	public function getFulfilled_order_total()
	{
		$deadlineDays=Yii::app()->params['orderDeadlineDays'];
		
		$customerId=Yii::app()->user->customer_id;
		$weekDeadline=date('Y-m-d', strtotime('+' . $deadlineDays . ' days'));
		
		$criteria=new CDbCriteria;
		$criteria->with = array('Box.Week');
		$criteria->condition = 'week_delivery_date<=:weekDeadline AND customer_id=:customerId';
		$criteria->params = array(':weekDeadline'=>$weekDeadline,':customerId'=>$customerId);
		$criteria->select = 'SUM((box_price * quantity) + (delivery_cost * quantity)) as fulfilled_total';
		
		$result = CustomerBox::model()->with('Box')->find($criteria);		
		return $result ? $result->fulfilled_total : 0;
	}
}