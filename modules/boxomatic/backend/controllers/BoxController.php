<?php

class BoxController extends BoxomaticController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';

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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','create','admin'),
				'roles'=>array('customer'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','duplicate','moveBox','update'),
				'roles'=>array('Admin'),
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
	public function actionView($id)
	{
		$model=$this->loadModel($id);
		$items=new CActiveDataProvider('BoxItem',array('criteria'=>array('condition'=>'box_id = ' . $model->box_id)));
		$this->render('view',array(
			'model'=>$model,
			'items'=>$items,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Box;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Box']))
		{
			$model->attributes=$_POST['Box'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->box_id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Box']))
		{
			$model->attributes=$_POST['Box'];
			if($model->save())
				$this->redirect(array('boxItem/create','date'=>$model->delivery_date_id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model=$this->loadModel($id);
		$dateId=$model->delivery_date_id;
		$model->delete();
		$this->redirect(array('boxItem/create','date'=>$dateId));
	}
	
	/**
	 * Duplicates a Box
	 * @param integer $id the ID of the Box to be duplicated
	 */
	public function actionDuplicate($id)
	{
		$model=$this->loadModel($id);
		$model->duplicate();
		$this->redirect(array('boxItem/create','date'=>$model->delivery_date_id));
	}
	
	/**
	 * Move a customer box to the selected box
	 * @param integer $from the ID of the box to move a customer box from
	 * @param integer $to the ID of the box to move a customer box to
	 * @param integer $cust the ID of the customer to move, if not specified a random customer will be chosen
	 */
	public function actionMoveBox($from, $to, $cust=null)
	{
		if($cust)
			$CustBoxFrom=UserBox::model()->findByPk($cust);
		else
			$CustBoxFrom=UserBox::random($from);
		
		$CustBoxFrom->box_id=$to;
		$CustBoxFrom->save();
		
		$this->redirect(array('boxItem/create','date'=>$CustBoxFrom->Box->delivery_date_id));
	}
	
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Box');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Box('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Box']))
			$model->attributes=$_GET['Box'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Box::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='box-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
