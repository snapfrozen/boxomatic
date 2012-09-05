<?php

class SnapEncrypt extends CApplicationComponent
{
	public $salt=null;
	
	public function init() 
	{
        if (!$this->salt) 
		{
            throw new CException('You must include a salt value for the SnapEncrypt component');
        }
		return parent::init();
	}
	
	/*
	 * One way encryption function
	 */
	public function encrypt($value)
	{
		return md5($value);
	}
}
?>
