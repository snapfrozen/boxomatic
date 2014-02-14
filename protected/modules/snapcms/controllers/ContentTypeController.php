<?php

class ContentTypeController extends DefaultController
{
	
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
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
				'actions'=>array('index'),
				'roles'=>array('View Content'),
			),
			array('allow',
				'actions'=>array('status','createTable','createFields'),
				'roles'=>array('Update Content Type Structure'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function actionIndex()
	{
		$data = ContentType::findAll();
		//ContentType::checkSchema();
		
		echo $this->render('index', array(
			'data' => $data
		));
	}
	
	public function actionStatus()
	{
		$data = ContentType::findAll();
		//ContentType::checkSchema();
		
		echo $this->render('status', array(
			'data' => $data
		));
	}
	
	public function actionCreateTable($id)
	{
		$model = ContentType::find($id);
		$model->createTable(); //0 is always returned. See http://php.net/manual/en/pdostatement.rowcount.php for more information.
		Yii::app()->user->setFlash('success', "Table <strong>$model->tableName</strong> created");
		$this->redirect(array('contentType/status'));
	}
	
	public function actionCreateFields($id)
	{
		$model = ContentType::find($id);
		$updated = $model->createFields();
		if($updated !== false) {
			Yii::app()->user->setFlash('success', "Created <strong>$updated</strong> field(s)");
		} else {
			Yii::app()->user->setFlash('error', "Could not create table <strong>$model->tableName</strong>");
		}
		$this->redirect(array('contentType/status'));
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}