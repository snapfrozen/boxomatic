<?php

class DefaultController extends Controller
{
	public $Content;
	
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl',
		);
	}
	
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}
	
	/*
	public function init()
	{
		if( !Yii::app()->user->hasState('user_id') )
			Yii::app()->user->setState('user_id', false);
		if( !Yii::app()->user->hasState('supplier_id') )
			Yii::app()->user->setState('supplier_id', false);
		if( !Yii::app()->user->hasState('shadow_id') )
			Yii::app()->user->setState('shadow_id', false);
		if( !Yii::app()->user->hasState('shadow_name') )
			Yii::app()->user->setState('shadow_name', false);
		if( !Yii::app()->user->hasState('full_name') )
			Yii::app()->user->setState('full_name', false);
	}
	 */
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('error','login','shop','captcha'),
				'users'=>array('*'),
			),
			array('allow',
				'actions'=>array('index','contact'),
				'roles'=>array('View Content'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex($date=null, $cat=null, $show=5, $location=null)
	{
		if(!$cat) {
			$cat = SnapUtil::config('boxomatic/supplier_product_feature_category');
		}
		
		$customerId = Yii::app()->user->user_id;
		$Customer = BoxomaticUser::model()->findByPk($customerId);
		$deadlineDays = SnapUtil::config('boxomatic/orderDeadlineDays');
		
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
					$extra->customer_delivery_date_id = $CustDD->id;
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
					$inventory = $model->inventory;
					if($quantity == 0) {
						$model->delete();
						if($inventory) {
							$inventory->delete();
						}
					} 
					else 
					{
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
					'customer_delivery_date_id'=>$CustDeliveryDate->id
				));
				if(!$extra) $extra = new OrderItem();

				$Purchase = SupplierPurchase::model()->findByPk($purchaseId);
				$SupplierProduct = $Purchase->supplierProduct;
				
				//give the customer the extra
				$extra->quantity += $quantity;
				$extra->customer_delivery_date_id = $CustDeliveryDate->id;
				$extra->supplier_purchase_id = $purchaseId;
				$extra->price = $Purchase->item_sales_price;
				$extra->packing_station_id = $SupplierProduct->packing_station_id;
				$extra->name = $SupplierProduct->name;
				$extra->unit = $SupplierProduct->unit;
				$updatedExtras[$extra->supplier_purchase_id] = $extra;
				$extra->save();
				
				/*
				if($extra->save())
				{
					//decrease inventory quantity;
					$inventory->quantity = -abs($extra->quantity);
					$inventory->supplier_product_id = $Purchase->supplier_product_id;
					$inventory->supplier_purchase_id = $purchaseId;
					$inventory->customer_delivery_date_item_id = $extra->id;
					$inventory->notes = 'Order: '.$extra->id.', Customer: '.$customerId;
					$inventory->save();
				}
				 */
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
		
		$inventory = Inventory::model()->getAvailableItems($date, $cat);
		$dpInventory = new CActiveDataProvider('Inventory');
		$dpInventory->setData($inventory);
		
		$orderedExtras = OrderItem::findCustomerExtras($customerId, $date);
		$dpOrderedExtras = new CActiveDataProvider('OrderItem');
		$dpOrderedExtras->setData($orderedExtras);
		
		$DeliveryDates=DeliveryDate::model()->with('Boxes')->findAll(array(
			'condition'=>'DATE_SUB(date, INTERVAL -1 week) > NOW() AND date < DATE_ADD(NOW(), INTERVAL 1 MONTH)',
			//'limit'=>$show
		));
		
		$this->render('order',array(
			'pastDeadline'=>$pastDeadline,
			'availableInventory'=>$dpInventory,
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
	public function actionShop($date=null, $cat=null, $show=5, $location=null)
	{
		if(!$cat) {
			$cat = SnapUtil::config('boxomatic/supplier_product_feature_category');
		}
		
		$customerId = Yii::app()->user->user_id;
		$Customer = BoxomaticUser::model()->findByPk($customerId);
		$deadlineDays = SnapUtil::config('boxomatic/orderDeadlineDays');
		
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
					$extra->customer_delivery_date_id = $CustDD->id;
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
					$inventory = $model->inventory;
					if($quantity == 0) {
						$model->delete();
						if($inventory) {
							$inventory->delete();
						}
					} 
					else 
					{
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
					'customer_delivery_date_id'=>$CustDeliveryDate->id
				));
				if(!$extra) $extra = new OrderItem();

				$Purchase = SupplierPurchase::model()->findByPk($purchaseId);
				$SupplierProduct = $Purchase->supplierProduct;
				
				//give the customer the extra
				$extra->quantity += $quantity;
				$extra->customer_delivery_date_id = $CustDeliveryDate->id;
				$extra->supplier_purchase_id = $purchaseId;
				$extra->price = $Purchase->item_sales_price;
				$extra->packing_station_id = $SupplierProduct->packing_station_id;
				$extra->name = $SupplierProduct->name;
				$extra->unit = $SupplierProduct->unit;
				$updatedExtras[$extra->supplier_purchase_id] = $extra;
				$extra->save();
				
				/*
				if($extra->save())
				{
					//decrease inventory quantity;
					$inventory->quantity = -abs($extra->quantity);
					$inventory->supplier_product_id = $Purchase->supplier_product_id;
					$inventory->supplier_purchase_id = $purchaseId;
					$inventory->customer_delivery_date_item_id = $extra->id;
					$inventory->notes = 'Order: '.$extra->id.', Customer: '.$customerId;
					$inventory->save();
				}
				 */
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
		
		$inventory = Inventory::model()->getAvailableItems($date, $cat);
		$dpInventory = new CActiveDataProvider('Inventory');
		$dpInventory->setData($inventory);
		
		$orderedExtras = OrderItem::findCustomerExtras($customerId, $date);
		$dpOrderedExtras = new CActiveDataProvider('OrderItem');
		$dpOrderedExtras->setData($orderedExtras);
		
		$DeliveryDates=DeliveryDate::model()->with('Boxes')->findAll(array(
			'condition'=>'DATE_SUB(date, INTERVAL -1 week) > NOW() AND date < DATE_ADD(NOW(), INTERVAL 1 MONTH)',
			//'limit'=>$show
		));
		
		$this->render('order',array(
			'pastDeadline'=>$pastDeadline,
			'availableInventory'=>$dpInventory,
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
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}