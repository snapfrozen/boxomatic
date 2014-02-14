<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;
	
	/**
	* Authenticates a user using the User data model.
	* @return boolean whether authentication succeeds.
	*/
	public function authenticate($validatePassword=true)
	{		
		$user=User::model()->resetScope()->findByAttributes(array('user_email'=>$this->username));
		if($user===null)
		{
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		} 
		else 
		{
			if($validatePassword && $user->password!==Yii::app()->snap->encrypt($this->password))
			{
				$this->errorCode=self::ERROR_PASSWORD_INVALID;
			} 
			else
			{
				$this->_id = $user->id;
				if(null===$user->last_login_time)
				{
					$lastLogin = time();
				} else {
					$lastLogin = strtotime($user->last_login_time);
				}
				$this->setState('last_login_time', $lastLogin); 
				$this->setState('customer_id', $user->customer_id); 
				$this->setState('supplier_id', $user->supplier_id); 
				$this->setState('first_name', $user->first_name);
				$this->setState('last_name', $user->last_name);
				$this->errorCode=self::ERROR_NONE;
			}
		}
		return !$this->errorCode;
	}
	
	/**
	 * Used by admin user to login as other users 
	 */
	public function loginAs($id, $curId)
	{	
		if(!Yii::app()->user->shadow_id) {
			$shadow=User::model()->resetScope()->findByPk($curId);
			$this->setState('shadow_id', $curId);
			$this->setState('shadow_name', $shadow->user_email);
			$this->setState('shadow_referrer', Yii::app()->request->getUrlReferrer());
		} else {
			$this->setState('shadow_id', false);
			$this->setState('shadow_name', false);
		}

		$user=User::model()->resetScope()->findByPk($id);
		$this->_id = $user->id;
		
		if(null===$user->last_login_time)
		{
			$lastLogin = time();
		} else {
			$lastLogin = strtotime($user->last_login_time);
		}
		
		$this->setState('last_login_time', $lastLogin); 
		$this->setState('customer_id', $user->customer_id); 
		$this->setState('supplier_id', $user->supplier_id); 
		
		$this->errorCode=self::ERROR_NONE;
		return !$this->errorCode;
	}
	
	public function getId()
	{
		return $this->_id;
	}
	
}