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
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('error'),
				'users'=>array('*'),
			),
			array('allow',
				'actions'=>array('index','contact','login','shop','captcha','register'),
				'roles'=>array('View Content'),
			),
			array('allow',
				'actions'=>array('welcome'),
				'roles'=>array('customer'),
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
		if(!$date) {
			$date = DeliveryDate::getCurrentDeliveryDateId();
		}
		
		$userId = Yii::app()->user->id;
		$User = BoxomaticUser::model()->findByPk($userId);
		
		$deadlineDays = SnapUtil::config('boxomatic/orderDeadlineDays');
		$updatedExtras = array();
		$updatedOrders = array();
		$Category = Category::model()->findByPk($cat);
		
		$DeliveryDate = DeliveryDate::model()->findByPk($date);
		$AllDeliveryDates = false;
		$pastDeadline = false;
		$Order = false;
		if($User)
		{
			if(!$User->location_id) {
				Yii::app()->user->setFlash('warning', 'Please set your location');
				$this->redirect(array('/shop/user/update','id'=>$User->id));
			}
			$Order = Order::model()->findByAttributes(array(
				'delivery_date_id' => $date,
				'user_id' => $userId,
			));
			if(!$Order) {
				$Order = new Order;
				$Order->delivery_date_id = $date;
				$Order->user_id = $userId;
				$Order->location_id = $User->location_id;
				$Order->save();
			}
			$AllDeliveryDates = DeliveryDate::model()->with('Locations')->findAll("Locations.location_id = " . $Order->location_id);
			$deadline = strtotime('+'.$deadlineDays.' days');

			
			$pastDeadline = strtotime($DeliveryDate->date) < $deadline;
			if($pastDeadline) {
				Yii::app()->user->setFlash('warning','Order deadline has passed, order cannot be changed.');
			}
		}
		
		if(!$User && (isset($_POST['supplier_purchases']) || isset($_POST['boxes']) ))
		{
			Yii::app()->user->setFlash('error','You must register to make an order.');
			$this->redirect(array('default/register'));
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
			$Order->location_id = $locationId;
			$Order->customer_location_id=$custLocationId;
			$Order->save();
			$Order->refresh();
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
			
			$dayOfWeek=date('N',strtotime($Order->DeliveryDate->date))+1;
			if($dayOfWeek == 8)
				$dayOfWeek = 1;
			
			$orderedExtras = OrderItem::findCustomerExtras($userId, $date);
			$orderedBoxes = UserBox::model()->with('Box')->findAllByAttributes(array('user_id'=>$User->id),'delivery_date_id='.$date);
			
			$DeliveryDates=DeliveryDate::model()->findAll("
					date >= '$startingFrom' AND
					date <=  DATE_ADD('$startingFrom', interval $monthsAdvance MONTH) AND
					date_sub(date, interval $deadlineDays day) > NOW() AND
					DAYOFWEEK(date) = '" . $dayOfWeek . "'");
			
			$n = 0;
			foreach($DeliveryDates as $DD)
			{		
				$Order = Order::model()->findByAttributes(array(
					'delivery_date_id' => $DD->id,
					'user_id' => $userId,
				));
				
				if(!$Order) 
				{
					$Order = new Order;
					$Order->delivery_date_id = $DD->id;
					$Order->user_id = $userId;
					$Order->location_id = $User->location_id;
					$Order->save();
				}
				
				//Delete any extras already ordered
				$TBDExtras = OrderItem::findCustomerExtras($userId, $DD->id);
				foreach($TBDExtras as $TBDExtra) {
					$TBDExtra->delete();
				}
				
				//Delete any boxes already ordered
				$TBDBoxes = UserBox::model()->with('Box')->findAllByAttributes(array('user_id'=>$User->id),'delivery_date_id='.$Order->delivery_date_id);
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
					$OrderItem = new OrderItem();
					//give the customer the extra
					$OrderItem->quantity = $orderedExt->quantity;
					$OrderItem->supplier_purchase_id = $orderedExt->supplier_purchase_id;
					$OrderItem->price = $orderedExt->price;
					$OrderItem->packing_station_id = $orderedExt->packing_station_id;
					$OrderItem->name = $orderedExt->name;
					$OrderItem->unit = $orderedExt->unit;
					$OrderItem->order_id = $Order->id;
					
					$OrderItem->save();
				}

				//Copy current days boxxes
				foreach($orderedBoxes as $orderedBox)
				{
					$EquivBox = Box::model()->findByAttributes(array('size_id'=>$orderedBox->Box->size_id,'delivery_date_id'=>$DD->id));
					$box = new UserBox();
					$box->attributes = $orderedBox->attributes;
					$box->user_box_id = null;
					$box->box_id = $EquivBox->box_id;
					$box->order_id = $Order->id;
					$box->save();
				}
				
			}
			
			Yii::app()->user->setFlash('success', 'Recurring order set.');
		}
		
		if(isset($_POST['btn_clear_orders'])) //clear orders button pressed
		{
			$orderedExtras = OrderItem::model()->with(array('Order'=>array('with'=>'DeliveryDate')))->findAll(
				"DATE_SUB(date, INTERVAL $deadlineDays DAY) > NOW() AND user_id = " . $User->id);
			
			foreach($orderedExtras as $ext) {
				$ext->delete();
			}
			
			//Get all boxes beyond the deadline date
			$Boxes=Box::model()->with('DeliveryDate')->findAll(
				"DATE_SUB(date, interval $deadlineDays day) > NOW()");

			foreach($Boxes as $Box)
			{
				$CustBox=UserBox::model()->findByAttributes(array('user_id'=>$User->id,'box_id'=>$Box->box_id));
				if($CustBox) {
					$CustBox->delete();
				}
			}
		}
		
		if(isset($_POST['extras']))
		{
			foreach($_POST['extras'] as $id=>$quantity) 
			{
				$model = OrderItem::model()->findByPk($id);
				if($model->Order->user_id == Yii::app()->user->id) 
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
				
				$OrderItem = OrderItem::model()->with('Order')->findByAttributes(array(
					'supplier_purchase_id'=>$purchaseId,
					'order_id'=>$Order->id
				));
				if(!$OrderItem) {
					$OrderItem = new OrderItem();
				}
				
				$Purchase = SupplierPurchase::model()->findByPk($purchaseId);
				$SupplierProduct = $Purchase->supplierProduct;
				
				//give the customer the extra
				$OrderItem->quantity = $quantity;
				$OrderItem->order_id = $Order->id;
				$OrderItem->supplier_purchase_id = $purchaseId;
				$OrderItem->price = $Purchase->item_sales_price;
				$OrderItem->packing_station_id = $SupplierProduct->packing_station_id;
				$OrderItem->name = $SupplierProduct->name;
				$OrderItem->unit = $SupplierProduct->unit;
				$updatedExtras[$OrderItem->supplier_purchase_id] = $OrderItem;
				$OrderItem->save();
				/*
				if($OrderItem->save())
				{
					//decrease inventory quantity;
					$inventory->quantity = -abs($OrderItem->quantity);
					$inventory->supplier_product_id = $Purchase->supplier_product_id;
					$inventory->supplier_purchase_id = $purchaseId;
					$inventory->order_item_id = $OrderItem->id;
					$inventory->notes = 'Order: '.$OrderItem->id.', Customer: '.$userId;
					$inventory->save();
				}
				 */
			}
		}
		
		if(isset($_POST['boxes']))
		{
			$Order = Order::model()->findByAttributes(array(
				'delivery_date_id' => $DeliveryDate->id,
				'user_id' => $userId,
			));

			if(!$Order) 
			{
				$Order = new Order;
				$Order->delivery_date_id = $DD->id;
				$Order->user_id = $userId;
				$Order->location_id = $User->location_id;
				$Order->save();
			}
				
			foreach($_POST['boxes'] as $boxId=>$quantity)
			{
				$Box=Box::model()->findByPk($boxId);
				
				$CustBoxes=UserBox::model()->with('Box')->findAll(array(	
					'condition'=>'user_id=:customerId AND size_id=:sizeId AND delivery_date_id=:deliveryDateId',
					'params'=>array(':customerId'=>$userId,':sizeId'=>$Box->size_id,':deliveryDateId'=>$Box->delivery_date_id)
				));
				
				$curQuantity=count($CustBoxes);
				$diff=$quantity-$curQuantity;
				
				if($diff > 0)
				{
					//Create extra customer box rows
					for($i=0; $i<$diff; $i++)
					{
						$CustBox=new UserBox;
						$CustBox->user_id=$userId;
						$CustBox->box_id=$boxId;
						$CustBox->quantity=1;
						$CustBox->delivery_cost=$User->Location->location_delivery_value;
						$CustBox->order_id = $Order->id;
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
		
		$orderedExtras = OrderItem::findCustomerExtras($userId, $date);
		$dpOrderedExtras = new CActiveDataProvider('OrderItem');
		$dpOrderedExtras->setData($orderedExtras);
		
		$DeliveryDates=DeliveryDate::model()->with('Boxes')->findAll(array(
			'condition'=>'DATE_SUB(date, INTERVAL -1 week) > NOW() AND date < DATE_ADD(NOW(), INTERVAL 1 MONTH)',
			//'limit'=>$show
		));
		
		$this->render('index',array(
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
			'Customer'=>$User,
			'CustDeliveryDate'=>$Order,
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

				mail(SnapUtil::config('boxomatic/adminEmail'),$subject,$model->body,$headers);
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
				$this->redirect(Yii::app()->user->getReturnUrl('/shop'));
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}
	
	
	public function actionRegister()
	{
		$model = new BoxomaticUser;
		$vars = array();

		if(isset($_POST['BoxomaticUser']))
		{
			$model->attributes = $_POST['BoxomaticUser'];
			$model->scenario = 'register';

			if($model->save())
			{
				if(!$model->Location->is_pickup)
				{
					$UserLoc=new UserLocation;
					$UserLoc->user_id=$model->id;
					$UserLoc->location_id=$model->location_id;
					$UserLoc->address=$model->user_address;
					$UserLoc->address2=$model->user_address2;
					$UserLoc->suburb=$model->user_suburb;
					$UserLoc->state=$model->user_state;
					$UserLoc->postcode=$model->user_postcode;
					$UserLoc->phone=!empty($model->user_phone)?$model->user_phone:$model->user_mobile;
					$UserLoc->save(false);
					$model->user_location_id = $UserLoc->customer_location_id;
				}

				$Auth = Yii::app()->authManager;
				$Auth->assign('customer',$model->id);

				$adminEmail = SnapUtil::config('boxomatic/adminEmail');
				$adminEmailFromName = SnapUtil::config('boxomatic/adminEmailFromName');
				//Send email
				$message = new YiiMailMessage('Welcome to ' . Yii::app()->name);
				$message->view = 'welcome';
				$message->setBody(array('User'=>$model,'newPassword'=>$_POST['BoxomaticUser']['password']), 'text/html');
				$message->addTo($adminEmail);
				$message->addTo($model->email);
				$message->setFrom(array($adminEmail => $adminEmailFromName));

				if(!@Yii::app()->mail->send($message)) {
					$mailError=true;
				}

				$identity=new UserIdentity($model->email, $_POST['BoxomaticUser']['password']);
				$identity->authenticate();

				Yii::app()->user->login($identity);
				BoxomaticUser::model()->updateByPk($identity->id, array('last_login_time'=>new CDbExpression('NOW()')));

				$this->redirect(array('default/welcome'));
			}
		}

		$model->password='';
		$model->password_repeat='';
		$vars['model'] = $model;
		
		// $this->render('register',array('model'=>$model));
		$this->render('register', $vars);
	}
	
	/**
	 * Welcome message.
	 */
	public function actionWelcome()
	{
		$User=BoxomaticUser::model()->findByPk(Yii::app()->user->id);
		$this->render('welcome',array('User'=>$User));
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