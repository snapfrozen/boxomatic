<?php

class DeliveryDateController extends Controller
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
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','updateCustomerBoxes','custBoxUpdate'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','generatePackingList','generateCustomerList','generateOrderList','generateCustomerListPdf'),
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
		$model=new DeliveryDate;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DeliveryDate']))
		{
			$model->attributes=$_POST['DeliveryDate'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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

		if(isset($_POST['DeliveryDate']))
		{
			$model->attributes=$_POST['DeliveryDate'];
			if($model->save())
				$this->redirect(array('admin'));
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
		$dataProvider=new CActiveDataProvider('DeliveryDate');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new DeliveryDate('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['DeliveryDate']))
			$model->attributes=$_GET['DeliveryDate'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	public function actionUpdateCustomerBoxes($date_id, $cust_id)
	{
		$CustBoxes=new CActiveDataProvider('CustomerBox',array(
			'criteria'=>array(
				'with'=>'Box',
				'condition'=>"Box.delivery_date_id=$date_id AND customer_id=$cust_id",
			)
		));

		$this->render('update_customer_boxes',array(
			'CustBoxes'=>$CustBoxes,
		));
	}
	
	public function actionCustBoxUpdate($id)
	{
		if(Yii::app()->request->isAjaxRequest && Yii::app()->user->shadow_id) 
		{
			$model=CustomerBox::model()->findByPk($id);
			if(isset($_POST['CustomerBox']))
			{
				$model->attributes=$_POST['CustomerBox'];
				echo CEditableGridView::validate($model);
				$model->save();
			}
			Yii::app()->end();
		}
	}
	
	/**
	 * Generate a packing list spreadsheet for a given date_id 
	 */
	public function actionGeneratePackingList($date)
	{
		$sql = '
		SELECT 
			SUM(item_quantity) as total, 
			
			GROUP_CONCAT(DISTINCT t.box_id ORDER BY t.box_id DESC) AS box_ids,
			GROUP_CONCAT(DISTINCT `box_item_id` ORDER BY `BoxItems`.box_id DESC) as box_item_ids,
			
			`BoxItems`.`box_item_id`,
			`BoxItems`.`item_name`,
			`BoxItems`.`item_unit`,
			`Supplier`.`name`
			
		FROM `boxes` `t`  

		LEFT OUTER JOIN `customer_boxes` `CustomerBoxes` 
			ON (`CustomerBoxes`.`box_id`=`t`.`box_id`)
		LEFT OUTER JOIN `box_items` `BoxItems` 
			ON (`BoxItems`.`box_id`=`t`.`box_id`)  
		LEFT OUTER JOIN `suppliers` `Supplier` 
			ON (`BoxItems`.`supplier_id`=`Supplier`.`id`)  

		WHERE (
			delivery_date_id=' . $date . ' 
			AND customer_box_id is not null
			AND (
				CustomerBoxes.status='.CustomerBox::STATUS_APPROVED.' OR
				CustomerBoxes.status='.CustomerBox::STATUS_DELIVERED.'
			)
		) 

		GROUP BY name,item_name 
		ORDER BY name;
		';
		
		echo $sql;exit;
		
		$connection=Yii::app()->db;
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$items=$dataReader->readAll();

		if(empty($items)) {
			echo 'No customer orders!';
			exit;
		}
		
		$phpExcelPath = Yii::getPathOfAlias('application.external.PHPExcel');
		
		$DateBoxes=Box::model()->with('BoxSize')->findAll(array(
			'condition'=>'delivery_date_id = '.$date,
			'order'=>'box_size_name DESC'
		));
		
		//disable Yii's Autoload because it messes with PHPExcel's autoloader
		spl_autoload_unregister(array('YiiBase','autoload'));  
		include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
		
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Supplier');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Item');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Total Quantity');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Unit');
		
		$alpha='ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$boxIds=explode(',',$items[0]['box_ids']);
		
		$pos=4;
		spl_autoload_register(array('YiiBase','autoload'));
		foreach($DateBoxes as $n=>$Box)
		{
			$custCount=$Box->customerCount;
			$objPHPExcel->getActiveSheet()->SetCellValue($alpha[$pos].'1', $Box->BoxSize->box_size_name . ' (' . $custCount . ')');
			$objPHPExcel->getActiveSheet()->getColumnDimension($alpha[$pos])->setAutoSize(true);
			$pos++;
		}
		//spl_autoload_unregister(array('YiiBase','autoload'));  
		$objPHPExcel->getActiveSheet()->getStyle("A1:".$alpha[$pos].'1')->applyFromArray(array("font" => array( "bold" => true)));
		
		$row=2;
		foreach($items as $item)
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$row, $item['name']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row, $item['item_name']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$row, $item['total']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$row, $item['item_unit']);
			
			$boxIds = explode(',',$item['box_ids']);
			$boxItemIds = explode(',',$item['box_item_ids']);
			$pos=4;
			//spl_autoload_register(array('YiiBase','autoload'));
			
			foreach($DateBoxes as $Box)
			{
				$BoxItem=null;
				$biPos=false;
				$biPos=array_search($Box->box_id, $boxIds);
				
				if($biPos !== false)
					$BoxItem=BoxItem::model()->findByAttributes(array('box_id'=>$Box->box_id, 'box_item_id'=>$boxItemIds[$biPos]));
				$quantity=($BoxItem && !empty($BoxItem->item_quantity)) ? $BoxItem->item_quantity : '0';
				$objPHPExcel->getActiveSheet()->SetCellValue($alpha[$pos].$row, $quantity);
				$pos++;
			}
			//spl_autoload_unregister(array('YiiBase','autoload'));  
			
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
		header('Content-Disposition: attachment; filename="packing-list-' . date('Ymd') . '"');
		$objWriter->save('php://output');

		exit;
	}
	
	
	/**
	 * Generate a packing list spreadsheet for a given date_id 
	 */
	public function actionGenerateOrderList($date)
	{
		$sql = '
		SELECT 
			SUM(item_quantity) as total,
			SUM(item_quantity * item_value) as total_price,
			item_value,
			
			GROUP_CONCAT(DISTINCT t.box_id ORDER BY t.box_id DESC) AS box_ids,
			GROUP_CONCAT(DISTINCT `box_item_id` ORDER BY `BoxItems`.box_id DESC) as box_item_ids,
			
			`BoxItems`.`box_item_id`,
			`BoxItems`.`item_name`,
			`BoxItems`.`item_unit`,
			`Supplier`.`name`
			
		FROM `boxes` `t`  

		LEFT OUTER JOIN `customer_boxes` `CustomerBoxes` 
			ON (`CustomerBoxes`.`box_id`=`t`.`box_id`)
		LEFT OUTER JOIN `box_items` `BoxItems` 
			ON (`BoxItems`.`box_id`=`t`.`box_id`)  
		LEFT OUTER JOIN `suppliers` `Supplier` 
			ON (`BoxItems`.`supplier_id`=`Supplier`.`id`)  

		WHERE (
			delivery_date_id=' . $date . ' 
			AND customer_box_id is not null
			AND 
			(
				CustomerBoxes.status='.CustomerBox::STATUS_APPROVED.' OR
				CustomerBoxes.status='.CustomerBox::STATUS_DELIVERED.'
			)
		) 

		GROUP BY name,item_name 
		ORDER BY name;
		';
		
		$connection=Yii::app()->db;
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$items=$dataReader->readAll();

		if(empty($items)) {
			echo 'No customer orders!';
			exit;
		}
		
		$phpExcelPath = Yii::getPathOfAlias('application.external.PHPExcel');
		
		$DateBoxes=Box::model()->with('BoxSize')->findAll(array(
			'condition'=>'delivery_date_id = '.$date,
			'order'=>'box_size_name DESC'
		));
		
		//disable Yii's Autoload because it messes with PHPExcel's autoloader
		spl_autoload_unregister(array('YiiBase','autoload'));  
		include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
		
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Supplier');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Item');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Total Quantity');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Unit');
		$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Unit Price');
		$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Total Price');
		
		$alpha='ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$boxIds=explode(',',$items[0]['box_ids']);
		
		$pos=6;
		spl_autoload_register(array('YiiBase','autoload'));
		foreach($DateBoxes as $n=>$Box)
		{
			$custCount=$Box->customerCount;
			$objPHPExcel->getActiveSheet()->SetCellValue($alpha[$pos].'1', $Box->BoxSize->box_size_name . ' (' . $custCount . ')');
			$objPHPExcel->getActiveSheet()->getColumnDimension($alpha[$pos])->setAutoSize(true);
			$pos++;
		}
		$objPHPExcel->getActiveSheet()->getStyle("A1:".$alpha[$pos].'1')->applyFromArray(array("font" => array( "bold" => true)));
		
		$row=2;
		foreach($items as $item)
		{
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$row, $item['name']);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$row, $item['item_name']);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$row, $item['total']);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$row, $item['item_unit']);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$row, $item['item_value']);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$row, $item['total_price']);
			
			$boxIds = explode(',',$item['box_ids']);
			$boxItemIds = explode(',',$item['box_item_ids']);
			$pos=6;
			
			foreach($DateBoxes as $Box)
			{
				$BoxItem=null;
				$biPos=false;
				$biPos=array_search($Box->box_id, $boxIds);
				
				if($biPos !== false)
					$BoxItem=BoxItem::model()->findByAttributes(array('box_id'=>$Box->box_id, 'box_item_id'=>$boxItemIds[$biPos]));
				$quantity=($BoxItem && !empty($BoxItem->item_quantity)) ? $BoxItem->item_quantity : '0';
				$objPHPExcel->getActiveSheet()->SetCellValue($alpha[$pos].$row, $quantity);
				$pos++;
			}
			$row++;
		}

		$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension("F")->setAutoSize(true);
		
		// Rename sheet
		$objPHPExcel->getActiveSheet()->setTitle('Packing List');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		
		header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="ordering-list-' . date('Ymd') . '"');
		$objWriter->save('php://output');

		exit;
	}
	
	
	/**
	 * Generate a packing list spreadsheet for a given date id 
	 */
	public function actionGenerateCustomerList($date)
	{
		$CustBoxes=CustomerBox::model()->with(array(
			'Box'=>array(
				'with'=>array(
					'BoxSize'
			)),
			'Customer'=>array(
				'with'=>array(
					'User',
				)
			)
		)
		)->findAll(array(
			'condition'=>'delivery_date_id='.$date.' AND (status='.CustomerBox::STATUS_APPROVED.' OR status='.CustomerBox::STATUS_DELIVERED.')',
			'order'=>'User.first_name'
		));

		$phpExcelPath = Yii::getPathOfAlias('application.external.PHPExcel');
		
		//disable Yii's Autoload because it messes with PHPExcel's autoloader
		spl_autoload_unregister(array('YiiBase','autoload'));  
		include($phpExcelPath . DIRECTORY_SEPARATOR . 'PHPExcel.php');
		
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Box Size');
		$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'First name');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Last name');
		$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Telephone');
		$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Mobile');
		$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Location');
		$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Address');
		//$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Address');

		$row=2;
		
		$sheet=$objPHPExcel->getActiveSheet();
		spl_autoload_register(array('YiiBase','autoload'));  
		foreach($CustBoxes as $CustBox)
		{
			$sheet->SetCellValue('A'.$row, $CustBox->Box->BoxSize->box_size_name);
			$sheet->SetCellValue('B'.$row, $CustBox->Customer->User->first_name);
			$sheet->SetCellValue('C'.$row, $CustBox->Customer->User->last_name);
			$sheet->SetCellValue('D'.$row, $CustBox->Customer->User->user_phone);
			$sheet->SetCellValue('E'.$row, $CustBox->Customer->User->user_mobile);
			$sheet->SetCellValue('F'.$row, $CustBox->delivery_location);
			$sheet->SetCellValue('G'.$row, $CustBox->delivery_address);
			//$sheet->SetCellValue('E'.$row, $CustBox->Customer->CustomerLocation ? $CustBox->Customer->CustomerLocation->full_address : "");
			$row++;
		}
		//spl_autoload_unregister(array('YiiBase','autoload'));  
		$objPHPExcel->getActiveSheet()->getStyle("A1:G1")->applyFromArray(array("font" => array( "bold" => true)));
		
		$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension("F")->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension("G")->setAutoSize(true);
		
		// Rename sheet
		$objPHPExcel->getActiveSheet()->setTitle('Customer List');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		
		header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="customer-list-' . date('Ymd') . '"');
		$objWriter->save('php://output');

		exit;
	}
	
	public function actionGenerateCustomerListPdf($date)
	{
		$mPDF1=Yii::app()->ePdf->mpdf();
		$mPDF1->SetTitle('Customer list');
		
		$CustBoxes=CustomerBox::model()->with(array(
			'Box'=>array(
				'with'=>array(
					'BoxSize'
			)),
			'Customer'=>array(
				'with'=>array(
					'User',
				)
			)
		)
		)->findAll(array(
			'condition'=>'delivery_date_id='.$date.' AND (status='.CustomerBox::STATUS_APPROVED.' OR status='.CustomerBox::STATUS_DELIVERED.')',
			'order'=>'User.first_name'
		));
		
		$stylesheet = file_get_contents(Yii::getPathOfAlias('webroot.css') . '/pdf.css');
		$mPDF1->WriteHTML($stylesheet, 1);

//		$this->renderPartial('customer_list_pdf', array('CustBoxes'=>$CustBoxes));
		
		$mPDF1->WriteHTML($this->renderPartial('customer_list_pdf', array('CustBoxes'=>$CustBoxes), true));
		$mPDF1->Output();
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=DeliveryDate::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='delivery-date-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
