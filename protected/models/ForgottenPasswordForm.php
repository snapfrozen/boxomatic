<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class ForgottenPasswordForm extends CFormModel
{
	public $username;
	private $_identity;
	public $User;
	
	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username', 'required'),
			array('username', 'userExists'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'username'=>'Email address',
		);
	}
	
	public function userExists($attribute)
	{
		$User=User::model()->findByAttributes( array('user_email'=>$this->$attribute) );
		
		$this->User=$User;
		
		if(!$User)
			$this->addError($attribute, 'Could not find user.');

	}
}
