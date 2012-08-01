<?php

class BoxItemController extends Controller
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
				'actions'=>array('index','view'),
				'roles'=>array('customer'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','create','update'),
				'roles'=>array('admin'),
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
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($weekId=null,$sizeId=null)
	{
		$model=new BoxItem;
			
		$GrowerItems=new GrowerItem('search');
		$GrowerItems->unsetAttributes();  // clear any default values
		if(isset($_GET['GrowerItem']))
			$GrowerItems->attributes=$_GET['GrowerItem'];
		
		$Weeks=new Week('search');
		$BoxSizes=new BoxSize('search');

		$CurrentBox=null;
		$BoxItemData=null;
		$criteria=new CDbCriteria();
		
		if($weekId && $sizeId)
		{
			$CurrentBox=Box::model()->find('week_id=:weekId AND size_id=:sizeId',array('weekId'=>$weekId,'sizeId'=>$sizeId));
			if(!$CurrentBox)
			{
				$CurrentBox=new Box;
				$CurrentBox->week_id=$weekId;
				$CurrentBox->size_id=$sizeId;
				$CurrentBox->box_price=BoxSize::model()->findByPk($sizeId)->box_size_price;
				$CurrentBox->save();
			}
			$criteria->condition = 'box_id = ' . $CurrentBox->box_id;
		}
		else
		{
			//We need to load the BoxItemData below, but we don't want it to have any results
			$criteria->condition = 'box_id = -1';
		}

		$BoxItemData=new CActiveDataProvider(BoxItem::model(), array(
			'criteria'=>$criteria,
			'pagination'=>false,
		));

		$this->performAjaxValidation($model);
		if(isset($_POST['BoxItem']))
		{
			if(!empty($_POST['BoxItem']['box_item_id']))
				$model=$this->loadModel($_POST['BoxItem']['box_item_id']);
			
			$model->attributes=$_POST['BoxItem'];
			$model->save();				
		}

		$this->render('create',array(
			'model'=>$model,
			'GrowerItems'=>$GrowerItems,
			'Weeks'=>$Weeks,
			'BoxSizes'=>$BoxSizes,
			'CurrentBox'=>$CurrentBox,
			'BoxItemData'=>$BoxItemData,
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

		if(isset($_POST['BoxItem']))
		{
			$model->attributes=$_POST['BoxItem'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->box_item_id));
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
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('BoxItem');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new BoxItem('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['BoxItem']))
			$model->attributes=$_GET['BoxItem'];

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
		$model=BoxItem::model()->findByPk((int)$id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='box-item-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
