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
 * @property string $user_name
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
			array('user_email, password','required'),
			array('customer_id, grower_id, update_user_id, create_user_id', 'numerical', 'integerOnly'=>true),
			array('user_email, password', 'length', 'max'=>255),
			array('user_email', 'unique'),
			array('user_email', 'email'),
			array('password', 'compare'),
			array('password_repeat', 'safe'),
			array('user_name, user_phone, user_mobile, user_suburb, user_state, user_postcode', 'length', 'max'=>45),
			array('user_address, user_address2, user_email', 'length', 'max'=>150),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_name, user_phone, user_mobile, user_address, user_address2, user_email, user_suburb, user_state, user_postcode, last_login_time, update_time, update_user_id, create_time, create_user_id', 'safe', 'on'=>'search'),
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
			'user_name' => 'Name',
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
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('grower_id',$this->grower_id);
		$criteria->compare('user_email',$this->user_email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('user_name',$this->user_name,true);
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

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Encrypt password before saving
	 */
	public function beforeSave()
	{
		$this->password = Yii::app()->snap->encrypt($this->password);
		return parent::beforeSave();
	}
	
	/**
	 * @return boolean whether the role was set or not
	 */
	public function setRole($role)
	{
		if(!Yii::app()->authManager->checkAccess($role, $this->id))
		{
			Yii::app()->authManager->assign($role, $this->id);
			return true;
		}
		return false;
	}
	

	/**
	* Only allow admins to access all user information
	*/
	public function defaultScope()
	{
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
	}
	
}