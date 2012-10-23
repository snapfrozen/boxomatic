<?php

/**
 * This is the model class for table "customer_boxes".
 *
 * The followings are the available columns in table 'customer_boxes':
 * @property integer $customer_box_id
 * @property integer $customer_id
 * @property integer $box_id
 * @property integer $quantity
 *
 * The followings are the available model relations:
 * @property Customers $customer
 * @property Boxes $box
 */
class CustomerBox extends CActiveRecord
{
	const STATUS_NOT_PROCESSED=0;
	const STATUS_APPROVED=1;
	const STATUS_DECLINED=2;
	//const STATUS_COLLECTED=3;
	
	public $week_total=null;		//holds an aggregate total for the week;
	public $fulfilled_total=null;	//holds an aggregate total for all orders before the deadline
	public $customer_first_name;
	public $customer_last_name;
	public $customer_box_price;
	public $customer_user_id;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CustomerBox the static model class
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
		return 'customer_boxes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, quantity', 'required'),
			array('box_id', 'required', 'message'=>'Please select a box'),
			array('status, customer_id, box_id, quantity', 'numerical', 'integerOnly'=>true, 'min'=>0),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('customer_user_id, customer_box_price, customer_first_name, customer_last_name, status, customer_box_id, customer_id, box_id, quantity', 'safe', 'on'=>'search'),
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
			'Box' => array(self::BELONGS_TO, 'Box', 'box_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'customer_box_id' => 'Customer Box',
			'customer_id' => 'Customer',
			'box_id' => 'Box',
			'quantity' => 'Quantity',
			'status' => 'Status',
			'customer_first_name' => 'First Name',
			'customer_last_name' => 'Last Name',
			'customer_box_price' => 'Box Price',
			'customer_user_id' => 'User ID',
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

		$criteria->compare('customer_box_id',$this->customer_box_id);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('box_id',$this->box_id);
		$criteria->compare('quantity',$this->quantity);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function boxSearch($week=null)
	{
		$criteria=new CDbCriteria;
		
		if($week) 
		{
			$criteria->together=true;
			$criteria->with=array(
				'Box'=>array(
					'condition'=>'week_id='.$week,
				),
				'Customer.User'
			);
			if($this->customer_first_name) {
				$criteria->compare('User.first_name',$this->customer_first_name,true);
			}
			if($this->customer_last_name) {
				$criteria->compare('User.last_name',$this->customer_last_name,true);
			}
			if($this->customer_box_price) {
				$criteria->compare('Box.box_price',$this->customer_box_price,true);
			}
			if($this->customer_user_id) {
				$criteria->compare('User.id',$this->customer_user_id,true);
			}
		}
		
		$criteria->compare('status',$this->status);
		$criteria->compare('customer_box_id',$this->customer_box_id);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('box_id',$this->box_id);
		$criteria->compare('quantity',$this->quantity);
		
		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
//			'pagination'=>false,
		));
	}
	
	public function getTotal_delivery_price()
	{
		$price = $this->Customer->Location->location_delivery_value * $this->quantity;
		return Yii::app()->snapFormat->currency($price);
	}
	
	public function getTotal_box_price()
	{
		$price = $this->Box->box_price * $this->quantity;
		return Yii::app()->snapFormat->currency($price);
	}
	
	public function getTotal_price()
	{
		$price = ($this->Box->box_price * $this->quantity) + ($this->Customer->Location->location_delivery_value * $this->quantity);
		return Yii::app()->snapFormat->currency($price);
	}
	
	/*
	 * Only allow changes before the delivery deadline if the user is not an admin
	 */
	public function beforeSave()
	{
		$Week=$this->Box->Week;

		if(	time() > strtotime($Week->deadline) && 
			!Yii::app()->user->checkAccess('admin') && 
			!isset(Yii::app()->user->shadow_id) && 
			Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->shadow_id)) {		
			
			return false;
		} else 
			return true;
	}
	
	
	/**
	* Only allow admins to access all user information
	*/
	public function defaultScope()
	{
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
	
	public static function findCustomerBox($weekId, $sizeId, $customerId)
	{
		$criteria=new CDbCriteria;
		$criteria->with='Box';
		$criteria->select='COUNT(customer_box_id) as quantity';
		$criteria->addCondition("Box.size_id=$sizeId");
		$criteria->addCondition("Box.week_id=$weekId");
		$criteria->addCondition("customer_id=$customerId");
		$box=self::model()->find($criteria);
		
		return $box;
	}
	
	public static function random($boxId)
	{
		$criteria=new CDbCriteria;
		$criteria->addCondition("box_id=$boxId");
		$criteria->order='RAND()';
		$box=self::model()->find($criteria);
		return $box;
	}
	
	/**
	* @return array issue type names indexed by type IDs
	*/
	public function getStatusOptions()
	{
		return array(
			self::STATUS_NOT_PROCESSED=>'Not Processed',
			self::STATUS_APPROVED=>'Approved',
			self::STATUS_DECLINED=>'Declined',
			//self::STATUS_COLLECTED=>'Collected',
		);
	}
	
	/**
	* @return string the status text display for the current issue
	*/
	public function getStatus_text()
	{
		$statusOptions=$this->statusOptions;
		return isset($statusOptions[$this->status]) ? $statusOptions[$this->status] : "unknown status ({$this->status})";
	}
	
	public function getDelivery_location()
	{
		$CustWeek=CustomerWeek::model()->findByAttributes(array(
			'customer_id'=>$this->customer_id,
			'week_id'=>$this->Box->week_id
		));
		return $CustWeek->delivery_location;
	}
}