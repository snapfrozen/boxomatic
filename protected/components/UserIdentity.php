<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		
		//user authentication from customers table
		$users = CHtml::listData(Customer::model()->findAll(), 'customer_email', 'customer_password');
		$users['admin'] = 'admin';
		
		if(!empty($this->username)
		and $this->username == 'admin'
		and $users[$this->username] == $this->password)
		    Yii::app()->user->setState('isAdmin', true);
		else
		    Yii::app()->user->setState('isAdmin', false);
		   
				
		if(!isset($users[$this->username]))
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else if($users[$this->username]!==$this->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
			$this->errorCode=self::ERROR_NONE;
		return !$this->errorCode;
	}
}