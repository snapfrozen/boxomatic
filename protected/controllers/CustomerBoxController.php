<?php

class CustomerBoxController extends Controller
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
				'actions'=>array('index','view','create','update','admin'),
				'roles'=>array('customer'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
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
		$model=$this->loadModel($id);
		$items=new CActiveDataProvider('BoxItem',array('criteria'=>array('condition'=>'box_id = ' . $model->box_id)));
		$this->render('view',array(
			'model'=>$model,
			'items'=>$items
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($boxId=null, $quantity=null)
	{
		$User=User::model()->findByPk(Yii::app()->user->id);
		
		$model=new CustomerBox;
		if(!$quantity)
			$model->quantity=1;//default 1 box
		else
			$model->quantity=$quantity;
		
		$model->customer_id=$User->Customer->customer_id;
		
		$Boxes=new Boxes('search');
		$Boxes->unsetAttributes();  // clear any default values
		if(isset($_GET['Boxes']))
			$Boxes->attributes=$_GET['Boxes'];
		
		$items=null;
		$SelectedBox=null;
		if($boxId)
		{
			$items=new CActiveDataProvider('BoxItem',array('criteria'=>array('condition'=>'box_id=' . (int)$boxId)));
			$SelectedBox=Box::model()->findByPk($boxId);
			$model->box_id=$boxId;
		}

		if(isset($_POST['CustomerBox']))
		{
			$model->attributes=$_POST['CustomerBox'];
			$model->customer_id=Yii::app()->user->id;
			$model->delivery_cost=$model->total_delivery_price;
			if($model->save())
				$this->redirect(array('admin','id'=>$model->customer_box_id));
		}

		$this->render('create',array(
			'model'=>$model,
			'Boxes'=>$Boxes,
			'items'=>$items,
			'SelectedBox'=>$SelectedBox,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id, $boxId=null, $quantity=null)
	{
		$model=$this->loadModel($id);

		$Boxes=new Boxes('search');
		$Boxes->unsetAttributes();  // clear any default values
		if(isset($_GET['Boxes']))
			$Boxes->attributes=$_GET['Boxes'];
		
		if($quantity)
			$model->quantity=$quantity;
		
		if($boxId)
		{
			$items=new CActiveDataProvider('BoxItem',array('criteria'=>array('condition'=>'box_id=' . (int)$boxId)));
			$SelectedBox=Box::model()->findByPk($boxId);
			$model->box_id=$boxId;
		}
		else
		{
			$items=new CActiveDataProvider('BoxItem',array('criteria'=>array('condition'=>'box_id=' . $model->box_id)));
			$SelectedBox=$model->Box;
		}

		if(isset($_POST['CustomerBox']))
		{
			$model->attributes=$_POST['CustomerBox'];
			$model->delivery_cost=$model->total_delivery_price;
			if($model->save())
				$this->redirect(array('admin','id'=>$model->customer_box_id));
		}

		$this->render('update',array(
			'model'=>$model,
			'Boxes'=>$Boxes,
			'items'=>$items,
			'SelectedBox'=>$SelectedBox,
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
		$dataProvider=new CActiveDataProvider('CustomerBox');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new CustomerBox('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CustomerBox']))
			$model->attributes=$_GET['CustomerBox'];

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
		$model=CustomerBox::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='customer-box-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
