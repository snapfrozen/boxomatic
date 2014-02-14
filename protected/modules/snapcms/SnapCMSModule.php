<?php

class SnapCMSModule extends CWebModule
{
	public $contentTypes;
	
	public function init()
	{
		$this->setImport(array(
			'snapcms.models.*',
			'snapcms.components.*',
			'snapcms.controllers.*',
		));

		//@TODO: Needed to prevent "yiic migrate" from trying to access Yii::app()->user
		//because this was breaking the initial migration. Maybe there's a better way 
		//to handle this?
		if(php_sapi_name() == 'cli' || Yii::app()->user->checkAccess('Access Backend'))
		{
			Yii::app()->setComponents(array(
				'errorHandler'=>array(
					'errorAction'=>'snapcms/default/error',
				))
			);
		}
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			//Yii::app()->theme = 'admin';
			if(Yii::app()->user->isGuest)
			{
				$controller->redirect(array('/site/login'));
			}
			else if(!Yii::app()->user->checkAccess('Access Backend')) {
				throw new CHttpException(403,Yii::t('yii','You are not authorized to perform this action.'));
			}
			
			$_SESSION['KCFINDER']['disabled'] = false; // enables the file browser in the admin
			$_SESSION['KCFINDER']['uploadURL'] = Yii::app()->baseUrl."/uploads/"; // URL for the uploads folder
			$_SESSION['KCFINDER']['uploadDir'] = Yii::app()->basePath."/../uploads/"; // path to the 

			$controller->layout = '//layouts/column2';
			
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
