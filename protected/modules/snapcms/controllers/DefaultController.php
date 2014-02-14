<?php

class DefaultController extends Controller
{	
	protected static $menuArray = array(
		//array('label'=>'List Users', 'url'=>array('/snapcms/user/index'), 'access'=>'View User'),
	);
	
	public $operations = array();
	
	public function actionIndex()
	{
		$this->layout = '//layouts/column2';
		$this->render('index');
	}
	
	public function actionError() 
	{
		if ($error = Yii::app()->errorHandler->error) 
		{
			if (Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else {
				//$this->layout = 'main';
				$this->render('error', $error);
			}
		}
	}
	
	public static function getMenuArray()
	{
		$menuArray = static::$menuArray;
		foreach($menuArray as &$item) {
			if(isset($item['access'])) {
				$item['visible'] = Yii::app()->user->checkAccess($item['access']);
			}
		}
		return $menuArray;
	}

}