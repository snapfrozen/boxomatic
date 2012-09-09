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
		$sql = '
		SELECT 
			SUM(item_quantity) as total, 
			
			GROUP_CONCAT(DISTINCT t.box_id ORDER BY t.box_id DESC) AS box_ids,
			GROUP_CONCAT(DISTINCT `box_item_id` ORDER BY `BoxItems`.box_id DESC) as box_item_ids,
			
			`BoxItems`.`box_item_id`,
			`BoxItems`.`item_name`,
			`BoxItems`.`item_unit`,
			`Grower`.`grower_name`
			
		FROM `boxes` `t`  

		LEFT OUTER JOIN `customer_boxes` `CustomerBoxes` 
			ON (`CustomerBoxes`.`box_id`=`t`.`box_id`)
		LEFT OUTER JOIN `box_items` `BoxItems` 
			ON (`BoxItems`.`box_id`=`t`.`box_id`)  
		LEFT OUTER JOIN `growers` `Grower` 
			ON (`BoxItems`.`grower_id`=`Grower`.`grower_id`)  

		WHERE (
			week_id=' . $week . ' 
			AND customer_box_id is not null
		) 

		GROUP BY grower_name,item_name 
		ORDER BY grower_name;
		';
		
		$connection=Yii::app()->db;
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$items=$dataReader->readAll();
		
		$phpExcelPath = Yii::getPathOfAlias('application.external.PHPExcel');
		
		//disable Yii's Autoload because it messes with PHPExcel's autoloader
		spl_autoload_unregister(array('YiiBase','autoload'));  
		include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
		
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		
		
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Grower');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Item');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Total Quantity');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Unit');
		
		$alpha='ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$boxIds=explode(',',$items[0]['box_ids']);
		
		$pos=4;
		foreach($boxIds as $n=>$boxId)
		{
			//A bit hack.. but it works!
			spl_autoload_register(array('YiiBase','autoload'));
			$Box=Box::model()->with('BoxSize')->findByPk($boxId);
			spl_autoload_unregister(array('YiiBase','autoload'));  

			$objPHPExcel->getActiveSheet()->SetCellValue($alpha[$pos].'1', $Box->BoxSize->box_size_name);
			$pos++;
		}
		$objPHPExcel->getActiveSheet()->getStyle("A1:".$alpha[$pos].'1')->applyFromArray(array("font" => array( "bold" => true)));
		
		$row=2;
		foreach($items as $item)
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$row, $item['grower_name']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row, $item['item_name']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$row, $item['total']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$row, $item['item_unit']);
			
			$boxIds = explode(',',$item['box_ids']);
			$boxItemIds = explode(',',$item['box_item_ids']);
			$pos=4;
			foreach($boxIds as $n=>$boxId)
			{
				//A bit hack.. but it works!
				spl_autoload_register(array('YiiBase','autoload'));
				$BoxItem=BoxItem::model()->findByAttributes(array('box_id'=>$boxId, 'box_item_id'=>$boxItemIds[$n]));
				spl_autoload_unregister(array('YiiBase','autoload'));  
				
				$objPHPExcel->getActiveSheet()->SetCellValue($alpha[$pos].$row, $BoxItem ? $BoxItem->item_quantity : 0);
				$pos++;
			}
			
			$row++;
		}
		
		$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);
		
		// Rename sheet
		$objPHPExcel->getActiveSheet()->setTitle('Packing List');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		
		header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="packing-list"');
		$objWriter->save('php://output');

		exit;
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
