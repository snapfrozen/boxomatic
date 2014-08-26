<?php

/**
 * This is the model class for table "customers".
 *
 * The followings are the available columns in table 'customers':
 * @property integer $user_id
 * @property integer $location_id
 * @property string $customer_notes
 *
 * The followings are the available model relations:
 * @property UserBoxes[] $userBoxes
 * @property Locations $location
 */
class Customer extends BoxomaticActiveRecord
{
	public $total_orders;
	public $last_order;
	public $extras_item_names;
	public $extras_total;
	public $search_full_name;
	
	public function behaviors()
	{
		return array(
			'activerecord-relation'=>array(
				'class'=>'boxomatic.extensions.active-relation-behavior.EActiveRecordRelationBehavior',
			)
		);
	}
	
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
		return $this->tablePrefix . 'customers';
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
			array('tag_names, location_id, customer_location_id', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('extras_total, search_full_name, extras_item_names, user_id, customer_notes', 'safe', 'on'=>'search'),
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
			'User' => array(self::HAS_ONE, 'User', 'user_id'),
			'UserBoxes' => array(self::HAS_MANY, 'UserBox', 'user_id'),
			'Orders' => array(self::HAS_MANY, 'Order', 'user_id'),
			'Boxes' => array(self::MANY_MANY, 'Box', 'user_boxes(user_id,box_id)'),
			'Location' => array(self::BELONGS_TO, 'Location', 'location_id'),
			'UserLocations' => array(self::HAS_MANY, 'UserLocation', 'user_id'),
			'UserLocation' => array(self::BELONGS_TO, 'UserLocation', 'customer_location_id'),
			'totalOrders'=>array(
                self::STAT, 'Box', 'user_boxes(user_id, box_id)', 
				'select' => 'SUM((box_price * quantity) + (delivery_cost * quantity))',
            ),
			'totalPayments'=>array(
                self::STAT, 'UserPayment', 'user_id', 'select' => 'SUM(payment_value)'
            ),
			'Payments'=>array(self::HAS_MANY, 'UserPayment', 'user_id'),
			'tags'=>array(self::MANY_MANY, 'Tag', 'customer_tags(user_id,tag_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'Customer',
			'customer_notes' => 'Customer Notes',
			'delivery_location_key' => 'Default Delivery Location',
			'location_id'=>'Location',
			'customer_location_id'=>'Delivery Location',
			'extras_item_names'=>'Extras',
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

		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('customer_notes',$this->customer_notes,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public function extrasSearch($date)
	{
		
		$c = new CDbCriteria;
		$c->addCondition('cdd.delivery_date_id = :date');
		$c->select = 't.user_id, GROUP_CONCAT(cddi.name SEPARATOR ", ") as extras_item_names';
		$c->params = array(':date'=>$date);
		//$c->with doesn't work very well with CActiveDataProvider
		$c->join = 
			'INNER JOIN customer_delivery_dates cdd ON cdd.user_id = t.user_id '. 
			'INNER JOIN customer_delivery_date_items cddi ON cddi.order_id = cdd.id';
		$c->group = 't.user_id';
		
		$c->compare('cddi.name',$this->extras_item_names,true);
		if(!empty($this->search_full_name)) {
			$c->join = 
			'INNER JOIN customer_delivery_dates cdd ON cdd.user_id = t.user_id '. 
			'INNER JOIN customer_delivery_date_items cddi ON cddi.order_id = cdd.id '.
			'INNER JOIN users u ON u.user_id = t.user_id';
			$c->compare('CONCAT(u.first_name, u.last_name)',$this->search_full_name,true);
		}
		
		return new CActiveDataProvider($this,array('criteria'=>$c));
	}
	
	public function getBalance()
	{
		return $this->totalPayments;
	}
	
	public function totalByDeliveryDate($dateId)
	{
		$customerId=$this->user_id;
		
		$criteria=new CDbCriteria;
		$criteria->condition = 'delivery_date_id=:dateId AND user_id=:customerId';
		$criteria->params = array(':dateId'=>$dateId,':customerId'=>$customerId);
		$criteria->select = 'SUM((box_price * quantity) + (delivery_cost * quantity)) as date_total';
		
		$boxes = UserBox::model()->with('Box')->find($criteria);		
		$boxTotal = $boxes ? $boxes->date_total : 0;
		
		$criteria->with = 'Order';
		$criteria->select = 'SUM(price * quantity) as date_total';
		$extras = OrderItem::model()->find($criteria);
		
		$extrasTotal = $extras ? $extras->date_total : 0;
		
		return $boxTotal + $extrasTotal;
	}
	
	public function extrasTotalByDeliveryDate($dateId)
	{
		$customerId=$this->user_id;
		
		$criteria=new CDbCriteria;
		$criteria->select = 'SUM(price * quantity) as date_total';
		$criteria->with = 'Order';
		$criteria->condition = 'delivery_date_id=:dateId AND user_id=:customerId';
		$criteria->params = array(':dateId'=>$dateId,':customerId'=>$customerId);
		$extras = OrderItem::model()->find($criteria);
		$extrasTotal = $extras ? $extras->date_total : 0;
		
		return $extrasTotal;
	}
	
	public function totalDeliveryByDeliveryDate($dateId)
	{
		$customerId=Yii::app()->user->user_id;
		
		$criteria=new CDbCriteria;
		$criteria->condition = 'delivery_date_id=:dateId AND user_id=:customerId';
		$criteria->params = array(':dateId'=>$dateId,':customerId'=>$customerId);
		$criteria->select = 'SUM(delivery_cost * quantity) as date_total';
		
		$result = UserBox::model()->with('Box')->find($criteria);		
		return $result ? $result->date_total : '';
	}
	
	public function totalBoxesByDeliveryDate($dateId)
	{
		$customerId=Yii::app()->user->user_id;
		
		$criteria=new CDbCriteria;
		$criteria->condition = 'delivery_date_id=:dateId AND user_id=:customerId';
		$criteria->params = array(':dateId'=>$dateId,':customerId'=>$customerId);
		$criteria->select = 'SUM((box_price * quantity)) as date_total';
		
		$result = UserBox::model()->with('Box')->find($criteria);		
		return $result ? $result->date_total : '';
	}
	
	public function getFulfilled_order_total()
	{
		$deadlineDays=SnapUtil::config('boxomatic/orderDeadlineDays');
		
		$customerId=Yii::app()->user->user_id;
		$deliveryDateDeadline=date('Y-m-d', strtotime('+' . $deadlineDays . ' days'));
		
		$criteria=new CDbCriteria;
		$criteria->with = array('Box.DeliveryDate');
		$criteria->condition = 'date<=:deliveryDateDeadline AND user_id=:customerId';
		$criteria->params = array(':deliveryDateDeadline'=>$deliveryDateDeadline,':customerId'=>$customerId);
		$criteria->select = 'SUM((box_price * quantity) + (delivery_cost * quantity)) as fulfilled_total';
		
		$result = UserBox::model()->with('Box')->find($criteria);		
		return $result ? $result->fulfilled_total : 0;
	}
	
	public function getDeliveryLocations()
	{
		//$this->DeliveryLocations;
		$pickupLocations=Location::model()->getPickupList();
		$custLocations=CHtml::listData($this->UserLocations,'location_key','full_location','delivery_label');
		return array_merge($custLocations,$pickupLocations);
	}
	
	public function getDelivery_location_key() 
	{
		if($this->customer_location_id)
			return $this->customer_location_id.'-'.$this->location_id;
		else {
			return $this->location_id;
		}
	}
	
	public function updateOrderDeliveryLocations()
	{
		//Make sure we have a fresh Customer object with now CDbExpressions set for attributes
		$Customer=self::model()->findByPk($this->user_id);
		
		$deadlineDays=SnapUtil::config('boxomatic/orderDeadlineDays');
		$CustDeliveryDates=Order::model()->with('DeliveryDate')->findAllByAttributes(array(
			'user_id'=>$Customer->user_id,
		),"date_sub(DeliveryDate.date, interval $deadlineDays day) > NOW()");
		
		foreach($CustDeliveryDates as $CustDate)
		{
			$CustDate->location_id=$Customer->location_id;
			if(empty($Customer->customer_location_id)) {
				$CustDate->customer_location_id=new CDbExpression('NULL');
			} else
				$CustDate->customer_location_id=$CustDate->customer_location_id;
			
			$CustDate->save();
		}
	}
	
	public function getDelivery_location()
	{
		if($this->UserLocation)
		{
			return $this->Location->location_name . ': ' . $this->UserLocation->full_address;
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
		$NextDelivery=DeliveryDate::model()->find(array(
			'condition'=>'date > NOW()',
			'order'=>'date ASC',
		));

		$criteria=new CDbCriteria();
		$criteria->with=array(
			'User'=>array(
				'joinType'=>'INNER JOIN'
			),
			'UserBoxes'=>array(
				'with'=>array(
					'Box'=>array(
						'with'=>array(
							'DeliveryDate'=>array()
						)
					)
				),
			),
		);
		$criteria->order='first_name ASC';
		$criteria->select='*, COUNT(UserBoxes.user_box_id) AS total_orders, MAX(DeliveryDate.date) as last_order';
		$criteria->group='t.user_id';
		$criteria->having='last_order="' . $NextDelivery->date . '"';
		//$criteria->addCondition('DeliveryDate.date < DATE_ADD(NOW(), INTERVAL 7 DAY)');
		//$criteria->addCondition('DeliveryDate.date > NOW()');
		
		return $this->findAll($criteria);
	}
	
	public function getTag_names()
	{
		$tags = CHtml::listData($this->tags,'id','name');
		return implode($tags, ', ');
	}
	
	public function setTag_names($data)
	{
		$tagNames = explode(',',$data);
		$criteria = new CDbCriteria();
		$criteria->addInCondition("name", $tagNames);
		
		$tags = Tag::model()->findAll($criteria);
		
		$currentTags = CHtml::listData($tags, 'id', 'name');
		$newTags = array_diff($tagNames, $currentTags);
		
		$newTagIds = array();
		foreach($newTags as $name)
		{
			$Tag = new Tag();
			$Tag->name = $name;
			$Tag->save();
			$newTagIds[] = $Tag->id;
		}
		
		$allTagIds = array_merge(array_keys($currentTags),$newTagIds);
		if(!empty($allTagIds)) {
			$this->tags = $allTagIds;
		} else {
			$this->tags = null;
		}
		$this->save();
		Tag::deleteUnusedTags();
	}
	
	public function getFull_name() {
		return CHtml::value($this,"User.full_name");
	}
	
}