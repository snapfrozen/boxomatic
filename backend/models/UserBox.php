<?php

/**
 * This is the model class for table "user_boxes".
 *
 * The followings are the available columns in table 'user_boxes':
 * @property integer $user_box_id
 * @property integer $user_id
 * @property integer $box_id
 * @property integer $quantity
 *
 * The followings are the available model relations:
 * @property Customers $customer
 * @property Boxes $box
 */
class UserBox extends BoxomaticActiveRecord
{
	const STATUS_NOT_PROCESSED=0;
	const STATUS_APPROVED=1;
	const STATUS_DECLINED=2;
	const STATUS_DELIVERED=3;
	
	public $date_total=null;		//holds an aggregate total for the date;
	public $fulfilled_total=null;	//holds an aggregate total for all orders before the deadline
	public $customer_full_name;
	public $customer_box_price;
	public $customer_user_id;
	public $customer_extras_price;
	public $search_extras;
	
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
	 * @return UserBox the static model class
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
		return $this->tablePrefix . 'user_boxes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, quantity', 'required'),
			array('box_id', 'required', 'message'=>'Please select a box'),
			array('status, user_id, box_id, quantity', 'numerical', 'integerOnly'=>true, 'min'=>0),
			array('delivery_cost','numerical'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('search_extras, customer_user_id, customer_box_price, customer_full_name, status, user_box_id, user_id, box_id, quantity', 'safe', 'on'=>'search'),
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
			'User' => array(self::BELONGS_TO, 'BoxomaticUser', 'user_id'),
			'Box' => array(self::BELONGS_TO, 'Box', 'box_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_box_id' => 'Customer Box',
			'user_id' => 'Customer',
			'box_id' => 'Box',
			'quantity' => 'Quantity',
			'status' => 'Status',
			'customer_full_name' => 'Customer',
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

		$criteria->compare('user_box_id',$this->user_box_id);
		$criteria->compare('user_id',$this->user_id);
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
				'User'
			);
			if($this->customer_full_name) {
				$criteria->compare('CONCAT(User.first_name,User.last_name)',$this->customer_full_name,true);
			}
			if($this->customer_box_price) {
				$criteria->compare('Box.box_price',$this->customer_box_price,true);
			}
			if($this->customer_user_id) {
				$criteria->compare('User.id',$this->customer_user_id,true);
			}
		}
		
		$criteria->compare('status',$this->status);
		$criteria->compare('user_box_id',$this->user_box_id);
		$criteria->compare('user_id',$this->user_id);
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
		$price = $this->User->Location->location_delivery_value * $this->quantity;
		return SnapFormat::currency($price);
	}
	
	public function getTotal_box_price()
	{
		$price = $this->Box->box_price * $this->quantity;
		return SnapFormat::currency($price);
	}
	
	public function getTotal_price()
	{
		$price = ($this->Box->box_price * $this->quantity) + ($this->User->Location->location_delivery_value * $this->quantity);
		return SnapFormat::currency($price);
	}
	
	/*
	 * Only allow changes before the delivery deadline if the user is not an admin
	 */
	public function beforeSave()
	{
		$DeliveryDate=$this->Box->DeliveryDate;

		if(	time() > strtotime($DeliveryDate->deadline) && 
			!Yii::app()->user->checkAccess('Admin') && 
			!isset(Yii::app()->user->shadow_id) && 
			Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->shadow_id)) {		
			
			return false;
		} else 
			return true;
	}
	
	public static function findUserBox($dateId, $sizeId, $userId)
	{
		$criteria=new CDbCriteria;
		$criteria->with='Box';
		$criteria->select='*, COUNT(user_box_id) as quantity';
		$criteria->addCondition("Box.size_id=$sizeId");
		$criteria->addCondition("Box.delivery_date_id=$dateId");
		$criteria->addCondition("user_id=$userId");
		$box=self::model()->find($criteria);
		
		//If there's no user_id the box wasn't found. (The aggregate COUNT 
		//function causes an empty row to be found)
		if(!$box || !$box->user_id)
			$box=null;
		
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
		$CustDeliveryDate=Order::model()->findByAttributes(array(
			'user_id'=>$this->user_id,
			'delivery_date_id'=>$this->Box->delivery_date_id
		));
		if($CustDeliveryDate)
			return $CustDeliveryDate->delivery_location;
		else
			return 'No location set!';
	}
	
	public function getDelivery_address()
	{
		$CustDeliveryDate=Order::model()->findByAttributes(array(
			'user_id'=>$this->user_id,
			'delivery_date_id'=>$this->Box->delivery_date_id
		));
		if($CustDeliveryDate)
			return $CustDeliveryDate->delivery_address;
		else
			return 'No location set!';
	}
	
	public function getQrCode()
	{
		$base=Yii::getPathOfAlias('webroot');
		$urlPath='/images/user_boxes/qr/' . $this->user_box_id . '.png';
		$filePath=$base.$urlPath;
		
		$url='http://'.Yii::app()->request->serverName;
		$url.=Yii::app()->createUrl('customerBox/setDelivered',array('id'=>$this->user_box_id));
		
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