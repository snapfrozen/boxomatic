<?php

class ReportController extends BoxomaticController
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
	
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('salesReport','creditReport'),
				'roles'=>array('Admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	/**
	 * Generate Reports
	 */
	public function actionCreditReport()
	{
		$xAxis=array();
		$yAxis=array();
		$series=array();
		$xAxisName='';
		$yAxisName='';
		
		$sql="SELECT MIN(payment_date) FROM customer_payments WHERE payment_date != '00-00-00 00:00:00'";
		$connection=Yii::app()->db; 
		$minDate=$connection->createCommand($sql)->queryScalar();
		
		$paymentTotals=array();
		$days=0;
		$timestamp=0;
		
		while($timestamp < time()) 
		{
			$timestamp=strtotime("+ $days days", strtotime($minDate));
			$timestampJs=$timestamp*1000;
			$minDate=date('Y-m-d h:i:s',$timestamp);
			
			$sql=
				"SELECT SUM(payment_value) as total
				FROM customer_payments
				WHERE payment_date < '$minDate'";

			$row=$connection->createCommand($sql)->queryRow();

			$paymentTotals[]=array($timestampJs, $row['total']);
			$days+=7;
		}
		$series[]=array('name'=>$minDate,'data'=>$paymentTotals);
		$yAxis=array('title'=>array('text'=>'Total payments'));
		//print_r($series);
		//print_r($series2);
			
		$this->render('reports_credit',array(
			'xAxis'=>$xAxis,
			'yAxis'=>$yAxis,
			'xAxisName'=>$xAxisName,
			'yAxisName'=>$yAxisName,
			'series'=>$series,
		));
	}

	/**
	 * Generate Reports
	 */
	public function actionSalesReport()
	{
		$xAxis=array();
		$yAxis=array();
		$series=array();
		$xAxisName='';
		$yAxisName='';
		$r=Yii::app()->request;
		if(isset($_POST['boxSales']))
		{	
			$xAxisName='DeliveryDate';
			$yAxisName='Boxes Sold';
			$dateFrom=$r->getPost($_POST['date_from'],'2012-01-01');
			$dateTo=$r->getPost($_POST['date_to'],'2020-01-01');

			//All boxes
			$sql=
				"SELECT d.date, count(user_box_id) as total
				FROM user_boxes cb
				JOIN boxes b ON cb.box_id=b.box_id
				JOIN delivery_dates d ON b.delivery_date_id=d.id
				WHERE 
					(
						cb.status=".UserBox::STATUS_APPROVED." OR 
						cb.status=".UserBox::STATUS_DELIVERED."
					)
					AND
					d.date > \"$dateFrom\" AND
					d.date < \"$dateTo\"
				GROUP BY d.date
				ORDER BY d.date ASC";
			
			$connection=Yii::app()->db; 
			$dataReader=$connection->createCommand($sql)->query();

			$allBoxesData=array();
			foreach($dataReader as $row) {
				//multiply by 1000 for milliseconds for javascript
				$allBoxesData[]=array(strtotime($row['date'])*1000, (int)$row['total']);
			}
			$series[]=array('name'=>'All Boxes','data'=>$allBoxesData);
			$yAxis=array('title'=>array('text'=>'Boxes Sold'), 'min'=>0);
			
			//Get data for each box size
			$BoxSizes=BoxSize::model()->findAll();
			foreach($BoxSizes as $BoxSize)
			{
				$sql=
					"SELECT d.date, count(user_box_id) as total
					FROM user_boxes cb
					JOIN boxes b ON cb.box_id=b.box_id
					JOIN delivery_dates d ON b.delivery_date_id=d.id
					WHERE 
						b.size_id=$BoxSize->id AND
						(
							cb.status=".UserBox::STATUS_APPROVED." OR 
							cb.status=".UserBox::STATUS_DELIVERED."
						) AND 
						d.date > \"$dateFrom\" AND
						d.date < \"$dateTo\"
					GROUP BY d.date
					ORDER BY d.date ASC";

				$dataReader=$connection->createCommand($sql)->query();

				$boxesData=array();
				foreach($dataReader as $row) {
					//multiply by 1000 for milliseconds for javascript
					$boxesData[]=array(strtotime($row['date'])*1000, (int)$row['total']);
				}
				$series[]=array('name'=>$BoxSize->box_size_name . ' Boxes','data'=>$boxesData);
			}
		}
		
		$this->render('reports_sales',array(
			'xAxis'=>$xAxis,
			'yAxis'=>$yAxis,
			'xAxisName'=>$xAxisName,
			'yAxisName'=>$yAxisName,
			'series'=>$series,
		));
	}
	
}
