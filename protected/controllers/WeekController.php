<?php

class WeekController extends Controller
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
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','generatePackingList'),
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
	public function actionCreate()
	{
		$model=new Week;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Week']))
		{
			$model->attributes=$_POST['Week'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->week_id));
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

		if(isset($_POST['Week']))
		{
			$model->attributes=$_POST['Week'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->week_id));
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
		$dataProvider=new CActiveDataProvider('Week');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Week('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Week']))
			$model->attributes=$_GET['Week'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Generate a packing list spreadsheet for a given week 
	 */
	public function actionGeneratePackingList($week)
	{
		$Boxes=Box::model()->with(array(
			'CustomerBoxes',
			'BoxItems'=>array(
				'select'=>'item_name,item_unit',
				'with'=>array(
					'Grower'=>array(
						'select'=>'grower_name'
					),
				)
			),
		))->findAll(array(
			'select'=>'size_id, SUM(item_quantity) as total_quantity, GROUP_CONCAT(t.box_id) AS box_ids',
			'condition'=>'week_id='.$week.' AND customer_box_id is not null',
			'group'=>'grower_name,item_name',
			'order'=>'grower_name',
		));
		
		$this->render('../site/index',array(
			
		));
		
//		$phpExcelPath = Yii::getPathOfAlias('application.external.PHPExcel');
//		
//		//disable Yii's Autoload because it messes with PHPExcel's autoloader
//		spl_autoload_unregister(array('YiiBase','autoload'));  
//		include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
//		
//		$objPHPExcel = new PHPExcel();
//		
//		$objPHPExcel->setActiveSheetIndex(0);
//		
//		$row=0;
//		foreach($Boxes as $Box)
//		{
//			echo $Box->size_id;
//			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$row, $Box->size_id);
//			$row++;
//		}
//		
//		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Hello');
//		$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'world!');
//		$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Hello');
//		$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'world!');
//
//		// Rename sheet
//		$objPHPExcel->getActiveSheet()->setTitle('Simple');
//		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//		
//		header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//		header('Content-Disposition: attachment; filename="packing-list"');
//		$objWriter->save('php://output');
//
//		exit;
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Week::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='week-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
