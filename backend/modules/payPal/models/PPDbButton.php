<?php

/**
 * This is the model class for table "{{ppbutton}}".
 *
 * CREATED: 2010-10-29
 * UPDATED: 2010-10-29
 *
 * The followings are the available columns in table '{{ppbutton}}':
 * @property string $name
 * @property string $web_site_code
 * @property string $email_link
 * @property string $hosted_button_id
 *
 * Camel case properties are also supported:
 * @property string $webSiteCode
 * @property string $emailLink
 * @property string $hostedButtonId
 *
 * The followings are the available model relations:
 *
 * @author Stian Liknes <stianlik@gmail.com>
 */
class PPDbButton extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return PPDbButton the static model class
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
		return '{{ppbutton}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name, hosted_button_id', 'required'),
			array('name', 'length', 'max'=>64),
			array('hosted_button_id', 'length', 'max'=>13),
			array('web_site_code, email_link', 'safe'),
			array('name, hosted_button_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'name' => 'Name',
			'web_site_code' => 'Web Site Code',
			'email_link' => 'Email Link',
			'hosted_button_id' => 'Hosted Button',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('name',$this->name,true);
		$criteria->compare('hosted_button_id',$this->hosted_button_id,true);
		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

	public function getWebSiteCode() {
		return $this->web_site_code;
	}

	public function setWebSiteCode($webSiteCode) {
		$this->web_site_code = $webSiteCode;
	}

	public function getHostedButtonId() {
		return $this->hosted_button_id;
	}

	public function setHostedButtonId($hostedButtonId) {
		$this->hosted_button_id = $hostedButtonId;
	}

	public function getEmailLink() {
		return $this->email_link;
	}

	public function setEmailLink($emailLink) {
		$this->email_link = $emailLink;
	}
}