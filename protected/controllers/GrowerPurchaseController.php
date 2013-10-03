<?php

class GrowerPurchaseController extends Controller
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
				'actions'=>array('index','view','create','update','admin','delete','duplicate','report'),
				'roles'=>array('admin'),
			),
//			array('allow', // allow authenticated user to perform 'create' and 'update' actions
//				'actions'=>array('create','update'),
//				'roles'=>array('admin'),
//			),
//			array('allow', // allow admin user to perform 'admin' and 'delete' actions
//				'actions'=>array('admin','delete'),
//				'roles'=>array('admin'),
//			),
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
	public function actionCreate()
	{
		$model=new GrowerPurchase;
		$GrowerItem=new GrowerItem;

		if(isset($_POST['GrowerItem']) && !empty($_POST['GrowerItem']['item_name']))
		{
			$GrowerItem->attributes=$_POST['GrowerItem'];
			$GrowerItem->grower_id=$_POST['grower_id'];
			$GrowerItem->save();
		}

		if(isset($_POST['GrowerPurchase']))
		{
			$model->attributes=$_POST['GrowerPurchase'];
			if(!empty($GrowerItem->item_id)) {
				$model->grower_item_id = $GrowerItem->item_id;
			}
			
			if($model->save())
				$this->redirect(array('admin','id'=>$model->grower_purchases_id));
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
		$GrowerItem=new GrowerItem;

		if(isset($_POST['GrowerItem']) && !empty($_POST['GrowerItem']['item_name']))
		{
			$GrowerItem->attributes=$_POST['GrowerItem'];
			$GrowerItem->grower_id=$_POST['grower_id'];
			$GrowerItem->save();
		}

		if(isset($_POST['GrowerPurchase']))
		{
			$model->attributes=$_POST['GrowerPurchase'];
			if(!empty($GrowerItem->item_id)) {
				$model->grower_item_id = $GrowerItem->item_id;
			}
			
			if($model->save())
				$this->redirect(array('admin','id'=>$model->grower_purchases_id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionDuplicate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$newGP = new GrowerPurchase;
		$newGP->attributes = $model->attributes;
		$newGP->grower_purchases_id = null;

		if($newGP->save())
			$this->redirect(array('update','id'=>$newGP->grower_purchases_id));

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
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('GrowerPurchase');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new GrowerPurchase('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['GrowerPurchase']))
			$model->attributes=$_GET['GrowerPurchase'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Generate Reports
	 */
	public function actionReport()
	{
		$xAxis=array();
		$yAxis=array();
		$series=array();
		$xAxisName='';
		$yAxisName='';
		$r=Yii::app()->request;
		if(isset($_POST['date_from']))
		{	
			$xAxisName='Week';
			$yAxisName='Boxes Sold';
			$dateFrom=$r->getPost('date_from','2012-01-01') . ' 00:00:00';
			$dateTo=$r->getPost('date_to','2020-01-01') . ' 00:00:00';
			$growerId=$r->getPost('Grower',false);
			$growerItemId=$r->getPost('GrowerItem',false);
			$organicStatus=$r->getPost('organicStatus',false);

			$c = new CDbCriteria();
			$c->select = '*, SUM(final_price) as total';
			$c->group = 'proposed_delivery_date';
			$c->addCondition('proposed_delivery_date > "'.$dateFrom.'"');
			$c->addCondition('proposed_delivery_date < "'.$dateTo.'"');
			if($growerId)
				$c->addCondition('Grower.grower_id = ' . $growerId);
			if($growerItemId)
				$c->addCondition('item_id = ' . $growerItemId);
			if($organicStatus)
				$c->addCondition('grower_certification_status = "' . $organicStatus .'"');
			
			$purchases = GrowerPurchase::model()->with(array('growerItem'=>array('with'=>'Grower')))->findAll($c);
			
			
			$priceData=array();
			foreach($purchases as $purchase) {
				//multiply by 1000 for milliseconds for javascript
				$priceData[]=array(strtotime($purchase->proposed_delivery_date)*1000, (float)$purchase->total);
			}
			
//			var_dump($_POST);
//			var_dump($dateFrom);
//			var_dump($dateTo);
//			var_dump($priceData);
//			
			$series[]=array('name'=>'Price','data'=>$priceData);
			$yAxis=array('title'=>array('text'=>'Price'), 'min'=>0);
		}
		
		//var_dump($series);
		
		$this->render('report',array(
			'xAxis'=>$xAxis,
			'yAxis'=>$yAxis,
			'xAxisName'=>$xAxisName,
			'yAxisName'=>$yAxisName,
			'series'=>$series,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=GrowerPurchase::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='grower-purchase-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
