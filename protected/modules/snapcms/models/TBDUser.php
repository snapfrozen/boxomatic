<?php

/**
 * This is the model class for table "{{users}}".
 *
 * The followings are the available columns in table '{{users}}':
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 */
class User extends CActiveRecord
{
	public $password_repeat;
	public $password_current;
	public $password_new;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{users}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('first_name, last_name, email, password', 'required'),
			array('first_name, last_name, email, password', 'length', 'max'=>255),
			array('email', 'unique'),
			array('email', 'email'),
			
			array('password', 'length', 'min'=>5),
            array('password_new, password_repeat', 'required', 'on'=>'new_password'),
            array('password_new, password_repeat', 'length', 'min'=>6, 'max'=>40),
			array('password_repeat', 'compare', 'compareAttribute' => 'password', 'on'=>'create'),
            array('password_repeat', 'compare', 'compareAttribute' => 'password_new', 'on'=>'new_password'),
			array('password_current', 'compareCurrentPassword', 'on'=>'new_password'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, first_name, last_name, email, password', 'safe', 'on'=>'search'),
		);
	}
		
	/**
	 * Rule for comparing the current users password
	 * @param type $attribute
	 * @param type $params 
	 */
	public function compareCurrentPassword($attribute, $params)
	{
		$matching = UserIdentity::doHash($this->password_current) == $this->password;
		if(!$matching)
			$this->addError($attribute, 'You did not enter your current password correctly');
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'email' => 'Email',
			'password' => 'Password',
			'password_current' => 'Current Password',
			'password_repeat' => 'Repeat Password',
			'password_new' => 'New Password',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getFull_name()
	{
		return $this->first_name . ' ' . $this->last_name;
	}
	
	public function getUser_groups()
	{
		if(!$this->id)
			return '';
		
		$authManager = Yii::app()->authManager;
		
		$roleNames = array();
		foreach($authManager->getAuthItems(CAuthItem::TYPE_ROLE, $this->id) as $role) {
			$roleNames[]=$role->name;
		}
		return implode(', ',$roleNames);
	}
	
	/**
	 * Hash passwords if required
	 * @return boolean 
	 */
	public function beforeSave()
	{	
		if($this->scenario == 'create' || $this->scenario == 'admin_change_password') {
			$this->password = UserIdentity::doHash($this->password);
		} else if($this->scenario == 'new_password') {
			$this->password = UserIdentity::doHash($this->password_new);
		}
		return parent::beforeSave();
	}
}
