<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property integer $customer_id
 * @property integer $grower_id
 * @property string $user_email
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string $user_phone
 * @property string $user_mobile
 * @property string $user_address
 * @property string $user_address2
 * @property string $user_suburb
 * @property string $user_state
 * @property string $user_postcode
 * @property string $last_login_time
 * @property string $create_time
 * @property integer $create_user_id
 * @property string $update_time
 * @property integer $update_user_id 
 */
class User extends SnapActiveRecord
{
	public $password_repeat;
	public $password_current;
	public $verifyCode;
	public $total_boxes;
	public $searchAdmin=false;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
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
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_email, password, first_name, last_name','required'),
			array('customer_id, grower_id, update_user_id, create_user_id', 'numerical', 'integerOnly'=>true),
			array('user_email, password', 'length', 'max'=>255),
			array('user_email', 'unique'),
			array('user_email', 'email'),
			//array('password', 'compare'),
			//array('password_repeat', 'safe'),
			array('first_name, last_name, user_phone, user_mobile, user_suburb, user_state, user_postcode', 'length', 'max'=>45),
			array('user_address, user_address2, user_email', 'length', 'max'=>150),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('first_name, last_name, full_name, user_phone, user_mobile, user_address, user_address2, user_email, user_suburb, user_state, user_postcode, last_login_time, update_time, update_user_id, create_time, create_user_id', 'safe', 'on'=>'search'),
			// verifyCode needs to be entered correctly
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements(),'on'=>'insert'),
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
			'Customer'=>array(self::BELONGS_TO,'Customer','customer_id'),
			'Grower'=>array(self::BELONGS_TO,'Grower','grower_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'customer_id' => 'Customer',
			'grower_id' => 'Grower',
			'password' => 'Password',
			'password_repeat' => 'Repeat Password',
			'password_current' => 'Current Password',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'full_name' => 'Full Name',
			'user_phone' => 'Phone',
			'user_mobile' => 'Mobile',
			'user_address' => 'Address',
			'user_address2' => '&nbsp;',
			'user_email' => 'Email',
			'user_suburb' => 'Suburb',
			'user_state' => 'State',
			'user_postcode' => 'Postcode',
			'last_login_time' => 'Last Login Time',
			'create_time' => 'Create Time',
			'create_user_id' => 'Created By',
			'update_time' => 'Last Updated',
			'update_user_id' => 'Updated by',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('grower_id',$this->grower_id);
		$criteria->compare('user_email',$this->user_email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('user_phone',$this->user_phone,true);
		$criteria->compare('user_mobile',$this->user_mobile,true);
		$criteria->compare('user_address',$this->user_address,true);
		$criteria->compare('user_address2',$this->user_address2,true);
		$criteria->compare('user_suburb',$this->user_suburb,true);
		$criteria->compare('user_state',$this->user_state,true);
		$criteria->compare('user_postcode',$this->user_postcode,true);
		$criteria->compare('last_login_time',$this->last_login_time,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user_id',$this->update_user_id);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user_id',$this->create_user_id);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user_id',$this->update_user_id);

		if($this->searchAdmin)
			$criteria->condition='customer_id is null AND grower_id is null';
		
		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>$pageSize,
			),
		));
	}
	
	/**
	 * Encrypt password before saving
	 */
	public function afterValidate()
	{
		$this->password = Yii::app()->snap->encrypt($this->password);
        $this->password_repeat = Yii::app()->snap->encrypt($this->password_repeat);
		return parent::beforeSave();
	}
	
	/**
	 * Foodbox only allows assignment of a single role
	 * @return boolean whether the role was set or not
	 */
	public function setRole($role)
	{
		if(!Yii::app()->authManager->isAssigned($role, $this->id))
		{
			Yii::app()->authManager->revoke($this->getRole(), $this->id);
			Yii::app()->authManager->assign($role, $this->id);
			return true;
		}
		return false;
	}
	
	/**
	 * Foodbox only allows assignment of a single role
	 * @return boolean 
	 */
	public function getRole()
	{
		$roles=Yii::app()->authManager->getRoles($this->id);
		if(!empty($roles)) {
			$keys=array_keys($roles);
			return array_pop($keys);
		} else {
			return false;
		}	
	}
	
	public function getFull_address()
	{
		$addr=array();
		if(!empty($this->user_address))
			$addr[]=$this->user_address;
		if(!empty($this->user_address2))
			$addr[]=$this->user_address2;
		if(!empty($this->user_suburb))
			$addr[]=$this->user_suburb;
		if(!empty($this->user_state))
			$addr[]=$this->user_state;
		if(!empty($this->user_postcode))
			$addr[]=$this->user_postcode;
			
		return implode(', ', $addr);
	}
	
	public function getFull_name()
	{
		return $this->first_name . ' ' . $this->last_name;
	}
	
	public function getBfb_id()
	{
		return 'BFB' . $this->id;
	}
	
	public function getFull_name_and_balance()
	{
		if($this->Customer)
			return $this->id . ': ' .$this->full_name . ' (' . Yii::app()->snapFormat->currency($this->Customer->balance) . ')';
		else
			return $this->id . ': ' .$this->full_name;
	}

	/**
	* Only allow admins to access all user information
	*/
	public function defaultScope()
	{
		
		/*
		//This messes up the unique validation :(
		if(!Yii::app()->user->checkAccess('admin')) 
		{
			return array(
				'condition' => "id = '" . Yii::app()->user->id . "'",
			);
		}
		else
		{
			return parent::defaultScope();
		}
		 */
		return parent::defaultScope();
	}

     /**
     * random password generation method
     *
     * @return string generated password
     * @param string length of password
     * @param int strength of password
     */
	public function generatePassword($length=9, $strength=0) 
	{
		$vowels = 'aeuy';
		$consonants = 'bdghjmnpqrstvz';
		if ($strength & 1) {
			$consonants .= 'BDGHJLMNPQRSTVWXZ';
		}
		if ($strength & 2) {
			$vowels .= "AEUY";
		}
		if ($strength & 4) {
			$consonants .= '23456789';
		}
		if ($strength & 8) {
			$consonants .= '@#$%';
		}
	 
		$password = '';
		$alt = time() % 2;
		for ($i = 0; $i < $length; $i++) {
			if ($alt == 1) {
				$password .= $consonants[(rand() % strlen($consonants))];
				$alt = 0;
			} else {
				$password .= $vowels[(rand() % strlen($vowels))];
				$alt = 1;
			}
		}
		return $password;
     }
	
}
