<?php

class SupplierPurchaseController extends Controller
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
				'roles'=>array('Admin'),
			),
//			array('allow', // allow authenticated user to perform 'create' and 'update' actions
//				'actions'=>array('create','update'),
//				'roles'=>array('Admin'),
//			),
//			array('allow', // allow admin user to perform 'admin' and 'delete' actions
//				'actions'=>array('admin','delete'),
//				'roles'=>array('Admin'),
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
		$model=new SupplierPurchase;
		$this->_doUpdate($model);

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
		$this->_doUpdate($model);

		$this->render('update',array(
			'model'=>$model,
		));
	}
	
	private function _doUpdate(&$model)
	{
		$SupplierProduct=new SupplierProduct;

		if(isset($_POST['SupplierProduct']) && !empty($_POST['SupplierProduct']['name']))
		{
			$SupplierProduct->attributes = $_POST['SupplierProduct'];
			$SupplierProduct->supplier_id = $_POST['supplier_id'];
			$SupplierProduct->save();
		}

		if(isset($_POST['SupplierPurchase']))
		{
			$model->attributes=$_POST['SupplierPurchase'];
			if(!empty($SupplierProduct->id)) {
				$model->supplier_product_id = $SupplierProduct->id;
			}
			
			if($model->save()) 
			{
				if(!empty($model->delivery_date))
				{
					$inventory = null;
					if($model->inventory) {
						$inventory = $model->inventory;
					} else {
						$inventory = new Inventory();
						$inventory->supplier_purchase_id = $model->id;
					}

					$inventory->quantity = $model->delivered_quantity;
					$inventory->supplier_product_id = $model->supplier_product_id;
					$inventory->delivery_date = $model->delivery_date;

					if(!$inventory->save()) {
						Yii::app()->user->setFlash('error','There was a problem updating inventory');
						print_r($inventory->errors);exit;
					}
				}
				$this->redirect(array('admin','id'=>$model->id));
			}
		}
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

		$newGP = new SupplierPurchase;
		$newGP->attributes = $model->attributes;
		$newGP->id = null;

		if($newGP->save())
			$this->redirect(array('update','id'=>$newGP->id));

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
		$dataProvider=new CActiveDataProvider('SupplierPurchase');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new SupplierPurchase('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['SupplierPurchase']))
			$model->attributes=$_GET['SupplierPurchase'];

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
			$xAxisName='DeliveryDate';
			$yAxisName='Boxes Sold';
			$dateFrom=$r->getPost('date_from','2012-01-01') . ' 00:00:00';
			$dateTo=$r->getPost('date_to','2020-01-01') . ' 00:00:00';
			$supplierId=$r->getPost('Supplier',false);
			$supplierProductId=$r->getPost('SupplierProduct',false);
			$organicStatus=$r->getPost('organicStatus',false);

			$c = new CDbCriteria();
			$c->select = '*, SUM(final_price) as total';
			$c->group = 'proposed_delivery_date';
			$c->addCondition('proposed_delivery_date > "'.$dateFrom.'"');
			$c->addCondition('proposed_delivery_date < "'.$dateTo.'"');
			if($supplierId)
				$c->addCondition('Supplier.id = ' . $supplierId);
			if($supplierProductId)
				$c->addCondition('id = ' . $supplierProductId);
			if($organicStatus)
				$c->addCondition('certification_status = "' . $organicStatus .'"');
			
			$purchases = SupplierPurchase::model()->with(array('supplierProduct'=>array('with'=>'Supplier')))->findAll($c);
			
			
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
		$model=SupplierPurchase::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='supplier-purchase-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
