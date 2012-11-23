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
	public $total_orders;
	public $last_order;
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
			array('customer_notes', 'length', 'max'=>500),
			array('location_id, customer_location_id', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('customer_id, customer_notes', 'safe', 'on'=>'search'),
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
			'User' => array(self::HAS_ONE, 'User', 'customer_id'),
			'CustomerBoxes' => array(self::HAS_MANY, 'CustomerBox', 'customer_id'),
			'Boxes' => array(self::MANY_MANY, 'Box', 'customer_boxes(customer_id,box_id)'),
			'Location' => array(self::BELONGS_TO, 'Location', 'location_id'),
			'CustomerLocations' => array(self::HAS_MANY, 'CustomerLocation', 'customer_id'),
			'CustomerLocation' => array(self::BELONGS_TO, 'CustomerLocation', 'customer_location_id'),
			'totalOrders'=>array(
                self::STAT, 'Box', 'customer_boxes(customer_id, box_id)', 
				'select' => 'SUM((box_price * quantity) + (delivery_cost * quantity))',
            ),
			'totalPayments'=>array(
                self::STAT, 'CustomerPayment', 'customer_id', 'select' => 'SUM(payment_value)'
            ),
			'Payments'=>array(self::HAS_MANY, 'CustomerPayment', 'customer_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'customer_id' => 'Customer',
			'customer_notes' => 'Customer Notes',
			'delivery_location_key' => 'Default Delivery Location',
			'location_id'=>'Location',
			'customer_location_id'=>'Delivery Location',
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
	
	public function getDeliveryLocations()
	{
		//$this->DeliveryLocations;
		$pickupLocations=Location::model()->getPickupList();
		$custLocations=CHtml::listData($this->CustomerLocations,'location_key','full_location','delivery_label');
		return array_merge($custLocations,$pickupLocations);
	}
	
	public function getDelivery_location_key() {
		if($this->customer_location_id)
			return $this->customer_location_id.'-'.$this->location_id;
		else {
			return $this->location_id;
		}
	}
	
	public function updateOrderDeliveryLocations()
	{
		//Make sure we have a fresh Customer object with now CDbExpressions set for attributes
		$Customer=self::model()->findByPk($this->customer_id);
		
		$deadlineDays=Yii::app()->params['orderDeadlineDays'];
		$CustWeeks=CustomerWeek::model()->with('Week')->findAllByAttributes(array(
			'customer_id'=>$Customer->customer_id,
		),"date_sub(Week.week_delivery_date, interval $deadlineDays day) > NOW()");
		
		foreach($CustWeeks as $CustWeek)
		{
			$CustWeek->location_id=$Customer->location_id;
			if(empty($Customer->customer_location_id)) {
				$CustWeek->customer_location_id=new CDbExpression('NULL');
			} else
				$CustWeek->customer_location_id=$CustWeek->customer_location_id;
			
			$CustWeek->save();
		}
	}
	
	public function getDelivery_location()
	{
		if($this->CustomerLocation)
		{
			return $this->Location->location_name . ': ' . $this->CustomerLocation->full_address;
		}
		else
		{
			return $this->Location->location_name;
		}
	}
	
	/**
	 * Find all customers that have no future orders 
	 */
	public function findAllWithNoOrders()
	{
		$criteria=new CDbCriteria();
		$criteria->with=array(
			'User'=>array(
				'joinType'=>'INNER JOIN'
			),
			'CustomerBoxes'=>array(
				'with'=>array(
					'Box'=>array(
						'with'=>array(
							'Week'=>array()
						)
					)
				),
			),
		);
		$criteria->order='first_name ASC';
		$criteria->select='*, COUNT(CustomerBoxes.customer_box_id) AS total_orders, MAX(Week.week_delivery_date) as last_order';
		$criteria->group='t.customer_id';
		$criteria->addCondition('Week.week_delivery_date < DATE_ADD(NOW(), INTERVAL 7 DAY)');
		$criteria->addCondition('Week.week_delivery_date > NOW()');
		
		return $this->findAll($criteria);
	}
	
}