<?php

class ContentController extends Controller
{	
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	
	public $meta_keywords='';
	public $meta_description='';
	public $meta_author='';
	
	public $Content;
	
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl',
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('index','view','getImage'),
				'roles'=>array('View Content'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id=null,$path=null)
	{
		//if path is given, find the content_id from the menu item
		if($path) {
			$MI=MenuItem::model()->findByAttributes(array('path'=>$path));
			$id = $MI ? $MI->content_id : null;
		} else {
			$MI=MenuItem::model()->findByAttributes(array('content_id'=>$id));
		}
		$Content=$this->loadModel($id);
		
		if(!Yii::app()->user->checkAccess('Update Content'))
		{
			$today = new DateTime();
			$publish_on = new DateTime($Content->publish_on);
			$unpublish_on = new DateTime($Content->unpublish_on);
			if(!$Content->published || $publish_on > $today || $unpublish_on < $today)
				throw new CHttpException(403,Yii::t('yii','You are not authorized to perform this action.'));
		}

		//$this->layout = '//layouts/column1';
		$view = 'view';
		
		if($this->getLayoutFile('//layouts/content_types/'.$Content->type))
			$this->layout = '//layouts/content_types/'.$Content->type;

		if($this->getViewFile('content_types/'.$Content->type))
			$view = 'content_types/'.$Content->type;		
		
		$this->meta_keywords = CHtml::value($Content,'meta_keywords');
		$this->meta_description = CHtml::value($Content,'meta_description');
		$this->meta_author = CHtml::value($Content,'UserCreated.full_name');
		
		//Used by the admin bar
		$this->Content = $Content;

		$this->render($view,array(
			'Content'=>$Content,
			'MenuItem'=>$MI,
		));
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Content the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Content::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
