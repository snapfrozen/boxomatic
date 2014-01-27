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
	const STATUS_DELIVERED=3;
	
	public $date_total=null;		//holds an aggregate total for the date;
	public $fulfilled_total=null;	//holds an aggregate total for all orders before the deadline
	public $customer_first_name;
	public $customer_last_name;
	public $customer_box_price;
	public $customer_user_id;
	
	public static $quantityOptions = array(
		'0'=>0,
		'1'=>1,
		'2'=>2,
		'3'=>3,
		'4'=>4,
		'5'=>5,
		'6'=>6,
		'7'=>7,
		'8'=>8,
		'9'=>9,
		'10'=>10
	);
	
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
			array('delivery_cost','numerical'),
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
	public function boxSearch($date=null)
	{
		$pageSize=isset($_GET['pageSize'])?$_GET['pageSize']:10;
		Yii::app()->user->setState('pageSize',$pageSize);
		
		$criteria=new CDbCriteria;
		
		if($date) 
		{
			$criteria->together=true;
			$criteria->with=array(
				'Box'=>array(
					'condition'=>'delivery_date_id='.$date,
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
			'pagination'=>array(
				'pageSize'=>$pageSize,
			),
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
		$DeliveryDate=$this->Box->DeliveryDate;

		if(	time() > strtotime($DeliveryDate->deadline) && 
			!Yii::app()->user->checkAccess('admin') && 
			!isset(Yii::app()->user->shadow_id) && 
			Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->shadow_id)) {		
			
			return false;
		} else 
			return true;
	}
	
	public static function findCustomerBox($dateId, $sizeId, $customerId)
	{
		$criteria=new CDbCriteria;
		$criteria->with='Box';
		$criteria->select='COUNT(customer_box_id) as quantity';
		$criteria->addCondition("Box.size_id=$sizeId");
		$criteria->addCondition("Box.delivery_date_id=$dateId");
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
			self::STATUS_DELIVERED=>'Collected/Delivered',
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
		$CustDeliveryDate=CustomerDeliveryDate::model()->findByAttributes(array(
			'customer_id'=>$this->customer_id,
			'delivery_date_id'=>$this->Box->delivery_date_id
		));
		return $CustDeliveryDate->delivery_location;
	}
	
	public function getDelivery_address()
	{
		$CustDeliveryDate=CustomerDeliveryDate::model()->findByAttributes(array(
			'customer_id'=>$this->customer_id,
			'delivery_date_id'=>$this->Box->delivery_date_id
		));
		return $CustDeliveryDate->delivery_address;
	}
	
	public function getQrCode()
	{
		$base=Yii::getPathOfAlias('webroot');
		$urlPath='/images/customer_boxes/qr/' . $this->customer_box_id . '.png';
		$filePath=$base.$urlPath;
		
		$url='http://'.Yii::app()->request->serverName;
		$url.=Yii::app()->createUrl('customerBox/setDelivered',array('id'=>$this->customer_box_id));
		
		Yii::import('ext.qrcode.QRCode');
		$code=new QRCode($url);
		$code->create($filePath);
		
		return Yii::app()->request->baseUrl.$urlPath;
	}
	
	public function setDelivered()
	{
		if($this->status!=self::STATUS_DELIVERED)
		{
			$this->status=self::STATUS_DELIVERED;
			return $this->save();
		}
		return false;
	}
}