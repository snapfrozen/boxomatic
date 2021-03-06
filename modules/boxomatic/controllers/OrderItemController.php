<?php

class OrderItemController extends BoxomaticController
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
				'actions'=>array('index','view','order'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','order','removeProduct','removeBoxes'),
				'roles'=>array('customer'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
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
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new OrderItem;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['OrderItem']))
		{
			$model->attributes=$_POST['OrderItem'];
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

		if(isset($_POST['OrderItem']))
		{
			$model->attributes=$_POST['OrderItem'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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
		$dataProvider=new CActiveDataProvider('OrderItem');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
	
	public function actionRemoveProduct($id)
	{
		$model = $this->loadModel($id);
		$date = $model->Order->delivery_date_id;
		if($model->Order->user_id != Yii::app()->user->user_id) {
			throw new CHttpException(403,'Access Denied. This item does not belong to you.');	
		}
		$model->delete();
		
		$this->redirect(array('order','date'=>$date));
	}
	
	public function actionRemoveBoxes($id)
	{
		$Box = Box::model()->findByPk($id);
		$date = $Box->delivery_date_id;
		$customerId = Yii::app()->user->user_id;
		
		if($Box->Order->user_id != Yii::app()->user->user_id) {
			throw new CHttpException(403,'Access Denied. This item does not belong to you.');
		}
		
		$CustBoxes=UserBox::model()->with('Box')->findAll(array(	
			'condition'=>'user_id=:customerId AND size_id=:sizeId AND delivery_date_id=:deliveryDateId',
			'params'=>array(':customerId'=>$customerId,':sizeId'=>$Box->size_id,':deliveryDateId'=>$Box->delivery_date_id)
		));
		foreach($CustBoxes as $CustBox)
		{
			$CustBox->delete();
		}
		
		$this->redirect(array('order','date'=>$date));
	}
	
	/**
	 * Manages all models.
	 */
	public function actionOrder($date=null, $cat=null, $show=5, $location=null)
	{
		if(!$cat) {
			$cat = SnapUtil::config('boxomatic/supplier_product_feature_category');
		}
		
		$customerId = Yii::app()->user->user_id;
		$Customer = Customer::model()->findByPk($customerId);
		$deadlineDays=SnapUtil::config('boxomatic/orderDeadlineDays');
		
		if(!$date) {
			$date = DeliveryDate::getCurrentDeliveryDateId();
		}
		
		$updatedExtras = array();
		$updatedOrders = array();
		$Category = Category::model()->findByPk($cat);
		
		$DeliveryDate = DeliveryDate::model()->findByPk($date);
		$AllDeliveryDates = false;
		$pastDeadline = false;
		$CustDeliveryDate = false;
		if($Customer)
		{
			$CustDeliveryDate = Order::model()->findByAttributes(array(
				'delivery_date_id' => $date,
				'user_id' => $customerId,
			));
			if(!$CustDeliveryDate) {
				$CustDeliveryDate = new Order;
				$CustDeliveryDate->delivery_date_id = $date;
				$CustDeliveryDate->user_id = $customerId;
				$CustDeliveryDate->location_id = $Customer->location_id;
				$CustDeliveryDate->save();
			}
			$AllDeliveryDates = DeliveryDate::model()->with('Locations')->findAll("Locations.location_id = " . $CustDeliveryDate->location_id);
			$deadline = strtotime('+'.$deadlineDays.' days');

			
			$pastDeadline = strtotime($DeliveryDate->date) < $deadline;
			if($pastDeadline) {
				Yii::app()->user->setFlash('warning','Order deadline has passed, order cannot be changed.');
			}
		}
		
		if(!$Customer && (isset($_POST['supplier_purchases']) || isset($_POST['boxes']) ))
		{
			Yii::app()->user->setFlash('error','You must register to make an order.');
			$this->redirect(array('site/register'));
		}
		
		if($location)
		{
			$locationId=$location;
			$custLocationId=new CDbExpression('NULL');
			if(strpos($locationId,'-'))
			{ //has a customer location
				$parts=explode('-',$locationId);
				$locationId=$parts[1];
				$custLocationId=$parts[0];
			}
			//$Location=Location::model()->findByPk($locationId);
			$CustDeliveryDate->location_id = $locationId;
			$CustDeliveryDate->customer_location_id=$custLocationId;
			$CustDeliveryDate->save();
			$CustDeliveryDate->refresh();
		}
		
		if(isset($_POST['btn_recurring'])) //recurring order button pressed
		{
			$monthsAdvance=(int)$_POST['months_advance'];
			$startingFrom=$_POST['starting_from'];
			$every=$_POST['every'];
			
			$locationId=$_POST['Order']['delivery_location_key'];
			$custLocationId=new CDbExpression('NULL');
			if(strpos($locationId,'-'))
			{ //has a customer location
				$parts=explode('-',$locationId);
				$locationId=$parts[1];
				$custLocationId=$parts[0];
			}
			
			$dayOfWeek=date('N',strtotime($CustDeliveryDate->DeliveryDate->date))+1;
			if($dayOfWeek == 8)
				$dayOfWeek = 1;
			
			$orderedExtras = OrderItem::findCustomerExtras($customerId, $date);
			$orderedBoxes = UserBox::model()->with('Box')->findAllByAttributes(array('user_id'=>$Customer->user_id),'delivery_date_id='.$date);
			
			$DeliveryDates=DeliveryDate::model()->findAll("
					date >= '$startingFrom' AND
					date <=  DATE_ADD('$startingFrom', interval $monthsAdvance MONTH) AND
					date_sub(date, interval $deadlineDays day) > NOW() AND
					DAYOFWEEK(date) = '" . $dayOfWeek . "'");
			
			$n = 0;
			foreach($DeliveryDates as $DD)
			{		
				$CustDD = Order::model()->findByAttributes(array(
					'delivery_date_id' => $DD->id,
					'user_id' => $customerId,
				));
				
				if(!$CustDD) 
				{
					$CustDD = new Order;
					$CustDD->delivery_date_id = $DD->id;
					$CustDD->user_id = $customerId;
					$CustDD->location_id = $CustDeliveryDate->location_id;
					$CustDD->save();
				}
				
				//Delete any extras already ordered
				$TBDExtras = OrderItem::findCustomerExtras($customerId, $DD->id);
				foreach($TBDExtras as $TBDExtra) {
					$TBDExtra->delete();
				}
				
				//Delete any extras already ordered
				$TBDBoxes = UserBox::model()->with('Box')->findAllByAttributes(array('user_id'=>$Customer->user_id),'delivery_date_id='.$CustDD->delivery_date_id);
				foreach($TBDBoxes as $TBDBox) {
					$TBDBox->delete();
				}
								
				$n++;
				if($n%2==0 && $every=='fortnight') {
					continue;
				}
				
				//Copy current days order
				foreach($orderedExtras as $orderedExt)
				{
					$extra = new OrderItem();
					
					//give the customer the extra
					$extra->quantity = $orderedExt->quantity;
					$extra->order_id = $CustDD->id;
					$extra->supplier_purchase_id = $orderedExt->supplier_purchase_id;
					$extra->price = $orderedExt->price;
					$extra->packing_station_id = $orderedExt->packing_station_id;
					$extra->name = $orderedExt->name;
					$extra->unit = $orderedExt->unit;
					$extra->save();
				}

				//Copy current days boxxes
				foreach($orderedBoxes as $orderedBox)
				{
					$EquivBox = Box::model()->findByAttributes(array('size_id'=>$orderedBox->Box->size_id,'delivery_date_id'=>$DD->id));
					$box = new UserBox();
					$box->attributes = $orderedBox->attributes;
					$box->user_box_id = null;
					$box->box_id = $EquivBox->box_id;
					$box->save();
				}
				
			}
			
			Yii::app()->user->setFlash('success', 'Recurring order set.');
		}
		
		if(isset($_POST['btn_clear_orders'])) //clear orders button pressed
		{
			$orderedExtras = OrderItem::model()->with(array('Order'=>array('with'=>'DeliveryDate')))->findAll(
				"DATE_SUB(date, INTERVAL $deadlineDays DAY) > NOW() AND user_id = " . $Customer->user_id);
			
			foreach($orderedExtras as $ext) {
				$ext->delete();
			}
			
			//Get all boxes beyond the deadline date
			$Boxes=Box::model()->with('DeliveryDate')->findAll(
				"DATE_SUB(date, interval $deadlineDays day) > NOW()");

			foreach($Boxes as $Box)
			{
				$CustBox=UserBox::model()->findByAttributes(array('user_id'=>$Customer->user_id,'box_id'=>$Box->box_id));
				if($CustBox) {
					$CustBox->delete();
				}
			}
		}
		
		if(isset($_POST['extras']))
		{
			foreach($_POST['extras'] as $id=>$quantity) 
			{
				$model = $this->loadModel($id);
				if($model->Order->user_id == Yii::app()->user->user_id) 
				{
					if($quantity == 0) {
						$model->delete();
					} else {
						$model->quantity=$quantity;
						$model->save();		
						$updatedOrders[$model->id] = $model;
					}
				}
			}
		}
		
		if(isset($_POST['supplier_purchases']))
		{
			foreach($_POST['supplier_purchases'] as $purchaseId=>$quantity) 
			{
				if($quantity==0) 
					continue;
				
				$extra = OrderItem::model()->with('Order')->findByAttributes(array(
					'supplier_purchase_id'=>$purchaseId,
					'order_id'=>$CustDeliveryDate->id
				));
				if(!$extra) $extra = new OrderItem();

				$Purchase = SupplierPurchase::model()->findByPk($purchaseId);
				$SupplierProduct = $Purchase->supplierProduct;
				
				//give the customer the extra
				$extra->quantity += $quantity;
				$extra->order_id = $CustDeliveryDate->id;
				$extra->supplier_purchase_id = $purchaseId;
				$extra->price = $Purchase->item_sales_price;
				$extra->packing_station_id = $SupplierProduct->packing_station_id;
				$extra->name = $SupplierProduct->name;
				$extra->unit = $SupplierProduct->unit;
				$updatedExtras[$extra->supplier_purchase_id] = $extra;
				$extra->save();
			}
		}
		
		if(isset($_POST['boxes']))
		{
			foreach($_POST['boxes'] as $boxId=>$quantity)
			{
				$Box=Box::model()->findByPk($boxId);
				
				$CustBoxes=UserBox::model()->with('Box')->findAll(array(	
					'condition'=>'user_id=:customerId AND size_id=:sizeId AND delivery_date_id=:deliveryDateId',
					'params'=>array(':customerId'=>$customerId,':sizeId'=>$Box->size_id,':deliveryDateId'=>$Box->delivery_date_id)
				));
				
				$curQuantity=count($CustBoxes);
				$diff=$quantity-$curQuantity;
				
				if($diff > 0)
				{
					//Create extra customer box rows
					for($i=0; $i<$diff; $i++)
					{
						$CustBox=new UserBox;
						$CustBox->user_id=$customerId;
						$CustBox->box_id=$boxId;
						$CustBox->quantity=1;
						$CustBox->delivery_cost=$Customer->Location->location_delivery_value;
						$CustBox->save();
					}
				}
				
				if($diff < 0)
				{
					//Remove any boxes the customer no longer wants;
					$diff=abs($diff);		
					for($i=0; $i<$diff; $i++)
					{
						$CustBoxes[$i]->delete();
					}
				}
			}
		}
		
		$orderedExtras = OrderItem::findCustomerExtras($customerId, $date);
		$dpOrderedExtras = new CActiveDataProvider('OrderItem');
		$dpOrderedExtras->setData($orderedExtras);
		
		$DeliveryDates=DeliveryDate::model()->with('Boxes')->findAll(array(
			'condition'=>'DATE_SUB(date, INTERVAL -1 week) > NOW() AND date < DATE_ADD(NOW(), INTERVAL 1 MONTH)',
			//'limit'=>$show
		));
		
		$this->render('order',array(
			'pastDeadline'=>$pastDeadline,
			'orderedExtras'=>$dpOrderedExtras,
			'updatedExtras'=>$updatedExtras,
			'updatedOrders'=>$updatedOrders,
			'DeliveryDate'=>$DeliveryDate,
			'DeliveryDates'=>$DeliveryDates,
			'AllDeliveryDates'=>$AllDeliveryDates,
			'model'=>new OrderItem,
			'Category'=>$Category,
			'Customer'=>$Customer,
			'CustDeliveryDate'=>$CustDeliveryDate,
			'curCat'=>$cat,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new OrderItem('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['OrderItem']))
			$model->attributes=$_GET['OrderItem'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return OrderItem the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=OrderItem::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param OrderItem $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='customer-delivery-date-item-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
