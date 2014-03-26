<?php

class CategoryController extends BoxomaticController
{
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
				'actions'=>array('index','view','create','update','admin','delete'),
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
		$this->render('view',array(
			'Category'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$Category=new Category;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($Category);

		if(isset($_POST['Category']))
		{
			$Category->attributes=$_POST['Category'];
			if($Category->save()) {
				Yii::app()->user->setFlash('success', 'Category Created.');
				if(!isset($_POST['save_and_continue'])) {
					$this->redirect(array('admin'));
				} else {
					$this->redirect(array('update','id'=>$Category->id));
				}
			}
			
		}

		$this->layout='//layouts/column1';
		$this->render('create',array(
			'Category'=>$Category,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$Category=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($Category);

		if(isset($_POST['Category']))
		{
			$Category->attributes=$_POST['Category'];
			if($Category->save()) {
				Yii::app()->user->setFlash('success', 'Category Saved.');
			}
			if(!isset($_POST['save_and_continue'])) {
				$this->redirect(array('admin'));
			}
		}

		$this->layout='//layouts/column1';
		$this->render('update',array(
			'Category'=>$Category,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();
		
		Yii::app()->user->setFlash('warning', 'Category Deleted.');

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Category');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$Category=new Category('search');
		$Category->unsetAttributes();  // clear any default values
		if(isset($_GET['Category']))
			$Category->attributes=$_GET['Category'];

		$this->render('admin',array(
			'Category'=>$Category,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Category the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$Category=Category::model()->findByPk($id);
		if($Category===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $Category;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Category $Category the model to be validated
	 */
	protected function performAjaxValidation($Category)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='category-form')
		{
			echo CActiveForm::validate($Category);
			Yii::app()->end();
		}
	}
}
