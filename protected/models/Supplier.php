<?php

/**
 * This is the model class for table "suppliers".
 *
 * The followings are the available columns in table 'suppliers':
 * @property integer $id
 * @property string $name
 * @property string $mobile
 * @property string $phone
 * @property string $address
 * @property string $address2
 * @property string $suburb
 * @property string $state
 * @property string $postcode
 * @property string $distance_kms
 * @property string $bank_account_name
 * @property string $bank_bsb
 * @property string $bank_acc
 * @property string $email
 * @property string $website
 * @property string $certification_status
 * @property string $order_days
 * @property string $produce
 * @property string $notes
 * @property string $payment_details
 *
 * The followings are the available model relations:
 * @property SupplierProducts[] $supplierProducts
 */
class Supplier extends CActiveRecord
{
	const STATUS_DELETED=0;
	const STATUS_ACTIVE=1;
	
	public $item_search;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Supplier the static model class
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
		return 'suppliers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
//			array('state', 'required'),
			array('longitude, lattitude', 'numerical'),
			array('company_name, Ordering, ABN, name, mobile, address2, suburb, postcode, distance_kms, bank_account_name, bank_bsb, bank_acc', 'length', 'max'=>45),
			array('phone, address, website, certification_status', 'length', 'max'=>150),
			array('state', 'length', 'max'=>50),
			array('email', 'length', 'max'=>100),
			array('order_days, certification_status, website, order_days', 'length', 'max'=>255),
			array('produce, notes, payment_details', 'length', 'max'=>500),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('item_search, id, name, mobile, phone, address, address2, suburb, state, postcode, distance_kms, bank_account_name, bank_bsb, bank_acc, email, website, certification_status, order_days, produce, notes, payment_details', 'safe', 'on'=>'search'),
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
			'SupplierProducts' => array(self::HAS_MANY, 'SupplierProduct', 'id'),
			'BoxItems' => array(self::HAS_MANY , 'BoxItem', 'box_item_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Supplier',
			'name' => 'Supplier Name',
			'mobile' => 'Mobile',
			'phone' => 'Phone',
			'address' => 'Street Address',
			'address2' => '',
			'suburb' => 'Suburb',
			'state' => 'State',
			'postcode' => 'Postcode',
			'distance_kms' => 'Distance(km)',
			'bank_account_name' => 'Bank Account Name',
			'bank_bsb' => 'Bank BSB',
			'bank_acc' => 'Bank Acc',
			'email' => 'Email',
			'website' => 'Website',
			'certification_status' => 'Certification Status',
			'order_days' => 'Order Days',
			'produce' => 'Produce',
			'notes' => 'Notes',
			'payment_details' => 'Payment Details',
			'item_search' => 'Produce',
			'lattitude' => 'Latitude',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($paginate=true)
	{	
		$pageSize=isset($_GET['pageSize'])?$_GET['pageSize']:10;
		Yii::app()->user->setState('pageSize',$pageSize);
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		if(!empty($this->item_search)) {
			$criteria->with=array('SupplierProducts');
			$searchItems=explode(',',$this->item_search);
			
			foreach($searchItems as $searchItem) {
				$criteria->addCondition('item_name LIKE "%' . $searchItem . '%"','OR');
			}
		}
	
		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('address2',$this->address2,true);
		$criteria->compare('suburb',$this->suburb,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('postcode',$this->postcode,true);
		$criteria->compare('distance_kms',$this->distance_kms,true);
		$criteria->compare('bank_account_name',$this->bank_account_name,true);
		$criteria->compare('bank_bsb',$this->bank_bsb,true);
		$criteria->compare('bank_acc',$this->bank_acc,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('website',$this->website,true);
		$criteria->compare('certification_status',$this->certification_status,true);
		$criteria->compare('order_days',$this->order_days,true);
		$criteria->compare('produce',$this->produce,true);
		$criteria->compare('notes',$this->notes,true);
		$criteria->compare('payment_details',$this->payment_details,true);

		$criteria->addCondition('status='.self::STATUS_ACTIVE);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=> $paginate ? array('pageSize'=>$pageSize) : $paginate,
		));
	}
	
	public static function getDropdownListItems()
	{
		$criteria = new CDbCriteria;
		$criteria->order = 'name';
		$criteria->addCondition('status='.self::STATUS_ACTIVE);	

		$items = self::model()->findAll($criteria);
		return CHtml::listData($items,'id','name');
	}
	
	public static function getOSDropdownListItems($supplierId=null)
	{
		$criteria = new CDbCriteria;
		$criteria->select = 'certification_status';
		$criteria->order = 'certification_status';
		$criteria->distinct = true;
		if($supplierId) {
			$criteria->addCondition('t.id='.$supplierId);	
		}
		
		$items = self::model()->findAll($criteria);
		return CHtml::listData($items,'certification_status','certification_status');
	}
}