<?php

class BoxItemController extends BoxomaticController
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
				'roles'=>array('customer'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array(
					'admin','delete','create','update','userBoxes',
					'processCustomers','processCustBox','refund','setDelivered','setApproved',
					'copyProductName'),
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
	
	public function actionCopyProductName($id)
	{
		$model = $this->loadModel($id);
		$model->item_name = $model->SupplierProduct->name;
		$model->save();
		$this->redirect(Yii::app()->request->urlReferrer);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($date=null,$item=null,$supplier=null)
	{
		Yii::app()->user->setReturnUrl($this->createUrl('create',array('date'=>$date,'item'=>$item,'supplier'=>$supplier)));
		$model=new BoxItem;
		$SelectedDeliveryDate=null;
		$NewItem=null;
		
		if(isset($_POST['bc']))
		{
			foreach($_POST['bc'] as $boxContents)
			{
				foreach($boxContents['BoxItem'] as $boxItem)
				{
					if(isset($boxItem['box_item_id']))
						$BoxItem=BoxItem::model()->findByPk($boxItem['box_item_id']);
					else 
						$BoxItem=new BoxItem;
					
					//Delete if boxItem exists and quantity has been set to 0
					if($BoxItem && isset($boxItem['box_item_id']) && $boxItem['item_quantity']==0) {
						$BoxItem->delete();
					}
					
					if($boxItem['item_quantity']>0) 
					{
						$BoxItem->attributes=$boxItem;
						$BoxItem->item_value=$boxContents['item_value'];
						$BoxItem->item_unit=$boxContents['item_unit'];
						$BoxItem->supplier_product_id=$boxContents['supplier_product_id'];
						if(isset($boxContents['item_name'])) {
							$BoxItem->item_name=$boxContents['item_name'];
						}
						
						$SP = $BoxItem->SupplierProduct;
						if(!$SP) 
						{
							$SP = SupplierProduct::model()->findByAttributes(array(
								'name'=>$boxContents['item_name'],
								'supplier_id'=>$boxContents['supplier_id'],
								'unit'=>$boxContents['item_unit'],
							));
							if(!$SP) {
								$SP = new SupplierProduct;
							}
							
							$SP->supplier_id=$boxContents['supplier_id'];
							if(isset($boxContents['item_name'])) {
								$SP->name=$boxContents['item_name'];
							}
							$SP->value=$boxContents['item_value'];
							$SP->unit=$boxContents['item_unit'];
							$SP->packing_station_id=$boxContents['packing_station_id'];
							$SP->available_from=1;
							$SP->available_to=12;
						}
						$SP->save();
						
						$BoxItem->supplier_product_id = $SP->id;
						$BoxItem->save();
					}
				}
			}			
		}
		
		$SupplierProducts=new SupplierProduct('search');
		$SupplierProducts->unsetAttributes();  // clear any default values
		if(isset($_GET['SupplierProduct']))
			$SupplierProducts->attributes=$_GET['SupplierProduct'];
		
		$DeliveryDates=DeliveryDate::model()->findAll();
		//$DeliveryDates=DeliveryDate::model()->findAll('date > NOW()');

		//Get the boxes for the selected date
		$DeliveryDateBoxes=null;
		if(!$date) {
			$date = DeliveryDate::getCurrentDeliveryDateId();
		}
		
		$SelectedDeliveryDate=DeliveryDate::model()->findByPk($date);
		$DeliveryDateBoxes=$SelectedDeliveryDate->MergedBoxes;
		
		//Item has been selected from inventory, if it doesn't exist in the date
		//Load it to be added as a new row up the top of the box item list
		$selectedItemId=null;
		if($item) 
		{
			$NewItem=SupplierProduct::model()->findByPk($item);
			$TmpItem=BoxItem::model()->with('Box')->find(
				'item_name=:itemName AND 
				supplier_id=:supplierId AND 
				item_unit=:itemUnit AND 
				item_value=:itemValue AND 
				Box.delivery_date_id=:deliveryDateId',
				array (
					':itemName'=>$NewItem->name,
					':supplierId'=>$NewItem->supplier_id,
					':itemUnit'=>$NewItem->unit,
					':itemValue'=>$NewItem->value,
					':deliveryDateId'=>$date,
				)
			);
			if($TmpItem) 
			{
				$selectedItemId=$TmpItem->box_item_id;
			}
			else 
			{
				foreach($DeliveryDateBoxes as $DeliveryDateBox)
				{
					$BoxItem=new BoxItem;
					$BoxItem->item_name=$NewItem->name;
					$BoxItem->supplier_id=$NewItem->supplier_id;
					$BoxItem->item_unit=$NewItem->unit;
					$BoxItem->item_value=$NewItem->value;
					$BoxItem->supplier_product_id=$NewItem->id;
					$BoxItem->packing_station_id=$NewItem->packing_station_id; 
					$BoxItem->item_quantity=1;
					$BoxItem->box_id=$DeliveryDateBox->box_id;
					$BoxItem->save();
					
					$selectedItemId=$BoxItem->box_item_id;
				}
			}
			//$this->redirect(array('boxItem/create','date'=>$date));
		}
		
		//User chose to add a new entry by clicking a supplier name
		if($supplier)
		{
			$TmpItem=BoxItem::model()->with('Box')->find(
				'item_name=:itemName AND 
				supplier_id=:supplierId AND 
				item_unit=:itemUnit AND 
				item_value=:itemValue AND 
				Box.delivery_date_id=:deliveryDateId',
				array (
					':itemName'=>'',
					':supplierId'=>(int)$supplier,
					':itemUnit'=>'KG',
					':itemValue'=>0,
					':deliveryDateId'=>$date,
				)
			);
			
			if($TmpItem) 
			{
				$selectedItemId=$TmpItem->box_item_id;
			}
			else 
			{
				foreach($DeliveryDateBoxes as $DeliveryDateBox)
				{
					$BoxItem=new BoxItem;
					$BoxItem->item_name='';
					$BoxItem->supplier_id=(int)$supplier;
					$BoxItem->item_unit='KG';
					$BoxItem->item_value=0;
					$BoxItem->item_quantity=1;
					$BoxItem->box_id=$DeliveryDateBox->box_id;
					$BoxItem->save();

					$selectedItemId=$BoxItem->box_item_id;
				}
			}
		}
		
		$this->performAjaxValidation($model);
		if(isset($_POST['BoxItem']))
		{
			if(!empty($_POST['BoxItem']['box_item_id']))
				$model=$this->loadModel($_POST['BoxItem']['box_item_id']);
			
			$model->attributes=$_POST['BoxItem'];
			$model->save();				
		}

		$Customer=new BoxomaticUser('search');
		$Customer->unsetAttributes();
		if(isset($_GET['BoxomaticUser']))
			$Customer->attributes = $_GET['BoxomaticUser'];
		
		$this->layout = '//layouts/full_width';
		$this->render('create',array(
			'model'=>$model,
			'SupplierProducts'=>$SupplierProducts,
			'DeliveryDates'=>$DeliveryDates,
			'DeliveryDateBoxes'=>$DeliveryDateBoxes,
			'SelectedDeliveryDate'=>$SelectedDeliveryDate,
			'selectedItemId'=>$selectedItemId,
			'Customer'=>$Customer,
		));
	}
	
	/**
	 * 
	 */
	public function actionUserBoxes($date=null)
	{
		$DeliveryDates=DeliveryDate::model()->findAll();
		if(!$date) {
			$date = DeliveryDate::getCurrentDeliveryDateId();
		}

		$CDD=new Order('search');
		$CDD->unsetAttributes();
		if(isset($_GET['Order']))
			$CDD->attributes = $_GET['Order'];
		
		$CDDsWithExtras = $CDD->extrasSearch($date);
		$SelectedDeliveryDate=DeliveryDate::model()->findByPk($date);

		$UserBoxes=new UserBox('search');
		$UserBoxes->unsetAttributes();  // clear any default values
		if(isset($_GET['UserBox']))
			$UserBoxes->attributes=$_GET['UserBox'];

		$this->render('user_boxes',array(
			'SelectedDeliveryDate'=>$SelectedDeliveryDate,
			'DeliveryDates'=>$DeliveryDates,
			'UserBoxes'=>$UserBoxes,
			'CDDsWithExtras'=>$CDDsWithExtras,
			'CDD'=>$CDD,
		));
	}
	
	/**
	 * 
	 */
	public function actionProcessCustomers($date)
	{
		$UserBoxes=UserBox::model()->with('Box')->findAll(array(
			'condition'=>'Box.delivery_date_id = ' . $date . ' AND status = ' . UserBox::STATUS_NOT_PROCESSED,
			'order'=>'box_price',//Attempt to process most expensive boxes first
		));
		
		foreach($UserBoxes as $CustBox)
		{
			$User=$CustBox->User;
			$Box=$CustBox->Box;
			if($User->balance - ($Box->box_price+$CustBox->delivery_cost) > SnapUtil::config('boxomatic/minimumCredit'))
			{
				$Payment=new UserPayment();
				$Payment->payment_value= -1*($Box->box_price+$CustBox->delivery_cost); //make price a negative value for payment table
				$Payment->payment_type='DEBIT';
				$Payment->payment_date=new CDbExpression('NOW()');
				$Payment->user_id=$CustBox->user_id;
				$Payment->staff_id=Yii::app()->user->id;
				
				$note='1 x ' . $Box->BoxSize->box_size_name . ' Box @ ' . SnapFormat::currency($Box->box_price);
				
				$tmpDel=(float)$CustBox->delivery_cost;
				if(!empty($tmpDel))
					$note.=' + ' . SnapFormat::currency($tmpDel) . ' Delivery';
				
				$Payment->payment_note=$note;
				$Payment->save();
				
				$CustBox->status=UserBox::STATUS_APPROVED;
				$CustBox->save();
				
			    //Box approved email
				$validator=new CEmailValidator();
				if($validator->validateValue($User->email)) 
				{
					$adminEmail = SnapUtil::config('boxomatic/adminEmail');
					$adminEmailFromName = SnapUtil::config('boxomatic/adminEmailFromName');
					$date = $Box->DeliveryDate->date;
					$message = new YiiMailMessage('Your order for '.$date.' has been approved');
					$message->view = 'customer_box_approved';
					$message->setBody(array('Customer'=>$User, 'UserBox' =>$CustBox), 'text/html');
					$message->addTo($User->email);
					$message->addTo($adminEmail);
					$message->setFrom(array($adminEmail => $adminEmailFromName));

					if(!@Yii::app()->mail->send($message)) {
						$mailError=true;
					}
				}
			}
			else
			{
				$CustBox->status=UserBox::STATUS_DECLINED;
				$CustBox->save();
				
				//Box declined email
				$validator=new CEmailValidator();
				if($validator->validateValue($User->email)) 
				{
					$adminEmail = SnapUtil::config('boxomatic/adminEmail');
					$adminEmailFromName = SnapUtil::config('boxomatic/adminEmailFromName');
					$date = $Box->DeliveryDate->date;
					$message = new YiiMailMessage('Your order for '.$date.' has been declined');
					$message->view = 'customer_box_declined';
					$message->setBody(array('Customer'=>$User, 'UserBox' => $CustBox), 'text/html');
					$message->addTo($User->email);
					$message->addTo($adminEmail);
					$message->setFrom(array($adminEmail => $adminEmailFromName));

					if(!@Yii::app()->mail->send($message))
					{
						$mailError=true;
					}
				}
			}
		}
		
		$CDD = new Order();
		$CDDsWithExtras = $CDD->extrasSearch($date)->getData();
		
		foreach($CDDsWithExtras as $CDD)
		{
			$User = $CDD->User;
			if($User->balance - $CDD->extras_total > SnapUtil::config('boxomatic/minimumCredit'))
			{
				$Payment=new UserPayment();
				$Payment->payment_value= -1*($CDD->extras_total); //make price a negative value for payment table
				$Payment->payment_type='DEBIT';
				$Payment->payment_date=new CDbExpression('NOW()');
				$Payment->user_id=$CDD->user_id;
				$Payment->staff_id=Yii::app()->user->id;
				
				$note='Extras bought on '. $CDD->DeliveryDate->date .' totalling:'. SnapFormat::currency($CDD->extras_total);

				$Payment->payment_note=$note;
				$Payment->save();
				/*
				$CustDD = Order::model()->findByAttributes(array(
					'user_id'=>$User->id,
					'delivery_date_id'=>$date,
				));
				var_dump($CDD->attributes);exit;
				$CustDD->status=Order::STATUS_APPROVED;
				$CustDD->save();
				*/
				$CDD->status=Order::STATUS_APPROVED;
				$CDD->save();
			}
			else 
			{
				//Box declined email
				$validator=new CEmailValidator();
				if($validator->validateValue($User->email)) 
				{
					$adminEmail = SnapUtil::config('boxomatic/adminEmail');
					$adminEmailFromName = SnapUtil::config('boxomatic/adminEmailFromName');
					$date = $Box->DeliveryDate->date;
					$message = new YiiMailMessage('Your custom order for '.$date.' has been declined');
					$message->view = 'customer_custom_order_declined';
					$message->setBody(array('Customer'=>$User, 'CDD' => $CDD), 'text/html');
					$message->addTo($User->email);
					$message->addTo($adminEmail);
					$message->setFrom(array($adminEmail => $adminEmailFromName));

					if(!@Yii::app()->mail->send($message))
					{
						$mailError=true;
					}
				}
				$CDD->status=Order::STATUS_DECLINED;
				$CDD->save();
			}
		}
		
		$this->redirect(array('userBoxes','date'=>$date));
	}
	
	/**
	 * 
	 */
	public function actionProcessCustBox($custBox)
	{
		$CustBox=UserBox::model()->findByPk($custBox);
		$User=$CustBox->User;
		if($User->balance >= $CustBox->Box->box_price+$CustBox->delivery_cost)
		{
			$Payment=new UserPayment();
			$Payment->payment_value= -1*($CustBox->Box->box_price+$CustBox->delivery_cost); //make price a negative value for payment table
			$Payment->payment_type='DEBIT';
			$Payment->payment_date=new CDbExpression('NOW()');
			$Payment->user_id=$CustBox->user_id;
			$Payment->staff_id=Yii::app()->user->id;
			
			$note='1 x ' . $CustBox->Box->BoxSize->box_size_name . ' Box @ ' . SnapFormat::currency($CustBox->Box->box_price);
			
			$tmpDel=(float)$CustBox->delivery_cost;
			if(!empty($tmpDel))
				$note.=' + ' . SnapFormat::currency($tmpDel) . ' delivery';
				
			$Payment->payment_note=$note;
			$Payment->save();

			$CustBox->status=UserBox::STATUS_APPROVED;
			$CustBox->save();
			
			//Box approved email
			$validator=new CEmailValidator();
			if($validator->validateValue($User->email)) 
			{
				$adminEmail = SnapUtil::config('boxomatic/adminEmail');
				$adminEmailFromName = SnapUtil::config('boxomatic/adminEmailFromName');
				$date = $CustBox->Box->DeliveryDate->date;
				$message = new YiiMailMessage('Your order for '.$date.' has been approved');
				$message->view = 'customer_box_approved';
				$message->setBody(array('Customer'=>$User, 'UserBox'=>$CustBox), 'text/html');
				$message->addTo($User->email);
				$message->addTo($adminEmail);
				$message->setFrom(array($adminEmail => $adminEmailFromName));

				if(!@Yii::app()->mail->send($message))
				{
					$mailError=true;
				}
			}
			
			Yii::app()->user->setFlash('success', "User included in this date's delivery.");
		}
		else
		{
			Yii::app()->user->setFlash('error', "Insufficient funds!");
		}
		$this->redirect(array('userBoxes','date'=>$CustBox->Box->delivery_date_id));
	}
	
	/**
	 * 
	 */
	public function actionRefund($custBox)
	{
		$CustBox=UserBox::model()->findByPk($custBox);
		
		if($CustBox)
		{
			$Payment=new UserPayment();
			$Payment->payment_value= 1*($CustBox->Box->box_price+$CustBox->delivery_cost); //make price a negative value for payment table
			$Payment->payment_type='CREDIT';
			$Payment->payment_date=new CDbExpression('NOW()');
			$Payment->user_id=$CustBox->user_id;
			$Payment->staff_id=Yii::app()->user->id;

			$note='REFUND FOR: 1 x ' . $CustBox->Box->BoxSize->box_size_name . ' Box @ ' . SnapFormat::currency($CustBox->Box->box_price);

			$tmpDel=(float)$CustBox->delivery_cost;
			if(!empty($tmpDel))
				$note.=' + ' . SnapFormat::currency($tmpDel) . ' delivery';

			$Payment->payment_note=$note;
			$Payment->save();
			$CustBox->delete();

			Yii::app()->user->setFlash('success', "Customer box has been excluded for delivery and refunded.");
		}
		else
		{
			Yii::app()->user->setFlash('success', "Could not find the given Customer Box");
		}

		$this->redirect(array('userBoxes','date'=>$CustBox->Box->delivery_date_id));
	}
	
	/**
	 * 
	 */
	public function actionSetApproved($custBox)
	{
		$CustBox=UserBox::model()->findByPk($custBox);
		
		if($CustBox)
		{
			$CustBox->status=UserBox::STATUS_APPROVED;
			$CustBox->save();
			Yii::app()->user->setFlash('success', "Customer box has been set to Approved.");
		}
		else
		{
			Yii::app()->user->setFlash('success', "Could not find the given Customer Box");
		}

		$this->redirect(array('userBoxes','date'=>$CustBox->Box->delivery_date_id));
	}
	
	/**
	 * 
	 */
	public function actionSetDelivered($custBox)
	{
		$CustBox=UserBox::model()->findByPk($custBox);
		
		if($CustBox)
		{
			$CustBox->status=UserBox::STATUS_DELIVERED;
			$CustBox->save();
			Yii::app()->user->setFlash('success', "Customer box has been set to Delivered.");
		}
		else
		{
			Yii::app()->user->setFlash('success', "Could not find the given Customer Box");
		}

		$this->redirect(array('userBoxes','date'=>$CustBox->Box->delivery_date_id));
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

		if(isset($_POST['BoxItem']))
		{
			$model->attributes=$_POST['BoxItem'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->box_item_id));
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
		$dataProvider=new CActiveDataProvider('BoxItem');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new BoxItem('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['BoxItem']))
			$model->attributes=$_GET['BoxItem'];

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
		$model=BoxItem::model()->findByPk((int)$id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='box-item-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
