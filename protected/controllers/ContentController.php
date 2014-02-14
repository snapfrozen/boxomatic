<?php

class ContentController extends Controller
{
	public $Content = null;
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','getFile'),
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
		if($path) 
		{
			$MI=MenuItem::model()->findByAttributes(array('path'=>$path));
			$id = $MI ? $MI->content_id : null;
		}
		$Content=$this->loadModel($id);
		
		$this->layout = '//layouts/column1';
		$view = 'view';
		
		if($this->getLayoutFile('//layouts/content_types/'.$Content->type))
			$this->layout = '//layouts/content_types/'.$Content->type;

		if($this->getViewFile('content_types/'.$Content->type))
			$view = 'content_types/'.$Content->type;		
		
		$this->render($view,array(
			'Content'=>$Content,
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Content');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
	
	/**
	 * Get a file associated with this model
	 * @param type $id
	 * @param type $field
	 */
	public function actionGetFile($id, $field)
	{
		$model=$this->loadModel($id);
		$base=Yii::getPathOfAlias('application.data');
		$filePath=$base.'/content_type_files/'.$model->ContentType->id.'/'.$field;
		$mime=false;
		
		if(function_exists('finfo_open'))
		{
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$mime = finfo_file($finfo, $filePath);
		}

		Yii::app()->request->xSendFile($filePath,array(
			'saveName'=>$model->$field,
			'mimeType'=>$mime,
			'terminate'=>false,
			'forceDownload'=>false,
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
		$this->Content=$model;
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
