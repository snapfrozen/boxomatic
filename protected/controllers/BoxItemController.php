<?php

class BoxItemController extends Controller
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
					'admin','delete','create','update','customerBoxes',
					'processCustomers','processCustBox','refund','setDelivered','setApproved'),
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
	public function actionCreate($date=null,$item=null,$supplier=null)
	{	
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
					if($BoxItem && isset($boxItem['box_item_id']) && $boxItem['item_quantity']==0) 
					{
						$BoxItem->delete();
					}
					
					if($boxItem['item_quantity']>0) 
					{
						$BoxItem->item_name=$boxContents['item_name'];
						$BoxItem->supplier_id=$boxContents['supplier_id'];
						$BoxItem->item_unit=$boxContents['item_unit'];
						$BoxItem->item_value=$boxContents['item_value'];
						$BoxItem->item_quantity=$boxItem['item_quantity'];
						$BoxItem->box_id=$boxItem['box_id'];
						$BoxItem->save();
					}
				}
				
				//Add an item to the inventory selected
				if(isset($boxContents['add_to_inventory']))
				{
					$SupplierProduct=SupplierProduct::model()->findByAttributes(array(
						'supplier_id'=>$boxContents['supplier_id'],
						'name'=>$boxContents['item_name'],
						'unit'=>$boxContents['item_unit'],
					));
					
					//Update the supplier item price already exists
					if($SupplierProduct)
					{
						$SupplierProduct->item_value=$boxContents['item_value'];
						$SupplierProduct->save();
					}
					else
					{
						$SupplierProduct=new SupplierProduct;
						$SupplierProduct->supplier_id=$boxContents['supplier_id'];
						$SupplierProduct->name=$boxContents['item_name'];
						$SupplierProduct->value=$boxContents['item_value'];
						$SupplierProduct->unit=$boxContents['item_unit'];
						$SupplierProduct->available_from=1;
						$SupplierProduct->available_to=12;
						$SupplierProduct->save();
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
					$BoxItem->item_quantity=1;
					$BoxItem->box_id=$DeliveryDateBox->box_id;
					$BoxItem->save();
					
					$selectedItemId=$BoxItem->box_item_id;
				}
			}
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
		
		$this->render('create',array(
			'model'=>$model,
			'SupplierProducts'=>$SupplierProducts,
			'DeliveryDates'=>$DeliveryDates,
			'DeliveryDateBoxes'=>$DeliveryDateBoxes,
			'SelectedDeliveryDate'=>$SelectedDeliveryDate,
			'selectedItemId'=>$selectedItemId,
		));
	}
	
	/**
	 * 
	 */
	public function actionCustomerBoxes($date=null)
	{
		$DeliveryDates=DeliveryDate::model()->findAll();

		$SelectedDeliveryDate=null;
		if($date) {
			$SelectedDeliveryDate=DeliveryDate::model()->findByPk($date);
		}
		
		$CustomerBoxes=new CustomerBox('search');
		
//		$model=new BoxItem('search');
		$CustomerBoxes->unsetAttributes();  // clear any default values
		if(isset($_GET['CustomerBox']))
			$CustomerBoxes->attributes=$_GET['CustomerBox'];
		
		$this->render('customer_boxes',array(
			'SelectedDeliveryDate'=>$SelectedDeliveryDate,
			'DeliveryDates'=>$DeliveryDates,
			'CustomerBoxes'=>$CustomerBoxes,
		));
	}
	
	/**
	 * 
	 */
	public function actionProcessCustomers($date)
	{
		$CustomerBoxes=CustomerBox::model()->with('Box')->findAll(array(
			'condition'=>'Box.delivery_date_id = ' . $date . ' AND status = ' . CustomerBox::STATUS_NOT_PROCESSED,
			'order'=>'box_price',//Attempt to process most expensive boxes first
		));
		
		foreach($CustomerBoxes as $CustBox)
		{
			$Customer=$CustBox->Customer;
			$Box=$CustBox->Box;
			if($Customer->balance >= $Box->box_price+$CustBox->delivery_cost)
			{
				$Payment=new CustomerPayment();
				$Payment->payment_value= -1*($Box->box_price+$CustBox->delivery_cost); //make price a negative value for payment table
				$Payment->payment_type='DEBIT';
				$Payment->payment_date=new CDbExpression('NOW()');
				$Payment->customer_id=$CustBox->customer_id;
				$Payment->staff_id=Yii::app()->user->id;
				
				$note='1 x ' . $Box->BoxSize->box_size_name . ' Box @ ' . Yii::app()->snapFormat->currency($Box->box_price);
				
				$tmpDel=(float)$CustBox->delivery_cost;
				if(!empty($tmpDel))
					$note.=' + ' . Yii::app()->snapFormat->currency($tmpDel) . ' Delivery';
				
				$Payment->payment_note=$note;
				$Payment->save();
				
				$CustBox->status=CustomerBox::STATUS_APPROVED;
				$CustBox->save();
				
			    //Box approved email
				$validator=new CEmailValidator();
				if($validator->validateValue($Customer->User->user_email)) 
				{
					$date = $Box->DeliveryDate->date;
					$message = new YiiMailMessage('Your order for '.$date.' has been approved');
					$message->view = 'customer_box_approved';
					$message->setBody(array('Customer'=>$Customer, 'CustomerBox' =>$CustBox), 'text/html');
					$message->addTo($Customer->User->user_email);
					$message->addTo('info@bellofoodbox.org.au');
					$message->setFrom(array(Yii::app()->params['adminEmail'] => Yii::app()->params['adminEmailFromName']));

					if(!@Yii::app()->mail->send($message))
					{
						$mailError=true;
					}
				}
			}
			else
			{
				$CustBox->status=CustomerBox::STATUS_DECLINED;
				$CustBox->save();
				
				//Box declined email
				$validator=new CEmailValidator();
				if($validator->validateValue($Customer->User->user_email)) 
				{
					$date = $Box->DeliveryDate->date;
					$message = new YiiMailMessage('Your order for '.$date.' has been declined');
					$message->view = 'customer_box_declined';
					$message->setBody(array('Customer'=>$Customer, 'CustomerBox' => $CustBox), 'text/html');
					$message->addTo($Customer->User->user_email);
					$message->addTo('info@bellofoodbox.org.au');
					$message->setFrom(array(Yii::app()->params['adminEmail'] => Yii::app()->params['adminEmailFromName']));

					if(!@Yii::app()->mail->send($message))
					{
						$mailError=true;
					}
				}
			}
		}
		
		$this->redirect(array('customerBoxes','date'=>$date));
	}
	
	/**
	 * 
	 */
	public function actionProcessCustBox($custBox)
	{
		$CustBox=CustomerBox::model()->findByPk($custBox);
		$Customer=$CustBox->Customer;
		if($Customer->balance >= $CustBox->Box->box_price+$CustBox->delivery_cost)
		{
			$Payment=new CustomerPayment();
			$Payment->payment_value= -1*($CustBox->Box->box_price+$CustBox->delivery_cost); //make price a negative value for payment table
			$Payment->payment_type='DEBIT';
			$Payment->payment_date=new CDbExpression('NOW()');
			$Payment->customer_id=$CustBox->customer_id;
			$Payment->staff_id=Yii::app()->user->id;
			
			$note='1 x ' . $CustBox->Box->BoxSize->box_size_name . ' Box @ ' . Yii::app()->snapFormat->currency($CustBox->Box->box_price);
			
			$tmpDel=(float)$CustBox->delivery_cost;
			if(!empty($tmpDel))
				$note.=' + ' . Yii::app()->snapFormat->currency($tmpDel) . ' delivery';
				
			$Payment->payment_note=$note;
			$Payment->save();

			$CustBox->status=CustomerBox::STATUS_APPROVED;
			$CustBox->save();
			
			//Box approved email
			$validator=new CEmailValidator();
			if($validator->validateValue($Customer->User->user_email)) 
			{
				$date = $CustBox->Box->DeliveryDate->date;
				$message = new YiiMailMessage('Your order for '.$date.' has been approved');
				$message->view = 'customer_box_approved';
				$message->setBody(array('Customer'=>$Customer, 'CustomerBox' => $CustBox), 'text/html');
				$message->addTo($Customer->User->user_email);
				$message->addTo('info@bellofoodbox.org.au');
				$message->setFrom(array(Yii::app()->params['adminEmail'] => Yii::app()->params['adminEmailFromName']));

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
		$this->redirect(array('customerBoxes','date'=>$CustBox->Box->delivery_date_id));
	}
	
	/**
	 * 
	 */
	public function actionRefund($custBox)
	{
		$CustBox=CustomerBox::model()->findByPk($custBox);
		
		if($CustBox)
		{
			$Payment=new CustomerPayment();
			$Payment->payment_value= 1*($CustBox->Box->box_price+$CustBox->delivery_cost); //make price a negative value for payment table
			$Payment->payment_type='CREDIT';
			$Payment->payment_date=new CDbExpression('NOW()');
			$Payment->customer_id=$CustBox->customer_id;
			$Payment->staff_id=Yii::app()->user->id;

			$note='REFUND FOR: 1 x ' . $CustBox->Box->BoxSize->box_size_name . ' Box @ ' . Yii::app()->snapFormat->currency($CustBox->Box->box_price);

			$tmpDel=(float)$CustBox->delivery_cost;
			if(!empty($tmpDel))
				$note.=' + ' . Yii::app()->snapFormat->currency($tmpDel) . ' delivery';

			$Payment->payment_note=$note;
			$Payment->save();
			$CustBox->delete();

			Yii::app()->user->setFlash('success', "Customer box has been excluded for delivery and refunded.");
		}
		else
		{
			Yii::app()->user->setFlash('success', "Could not find the given Customer Box");
		}

		$this->redirect(array('customerBoxes','date'=>$CustBox->Box->delivery_date_id));
	}
	
	/**
	 * 
	 */
	public function actionSetApproved($custBox)
	{
		$CustBox=CustomerBox::model()->findByPk($custBox);
		
		if($CustBox)
		{
			$CustBox->status=CustomerBox::STATUS_APPROVED;
			$CustBox->save();
			Yii::app()->user->setFlash('success', "Customer box has been set to Approved.");
		}
		else
		{
			Yii::app()->user->setFlash('success', "Could not find the given Customer Box");
		}

		$this->redirect(array('customerBoxes','date'=>$CustBox->Box->delivery_date_id));
	}
	
	/**
	 * 
	 */
	public function actionSetDelivered($custBox)
	{
		$CustBox=CustomerBox::model()->findByPk($custBox);
		
		if($CustBox)
		{
			$CustBox->status=CustomerBox::STATUS_DELIVERED;
			$CustBox->save();
			Yii::app()->user->setFlash('success', "Customer box has been set to Delivered.");
		}
		else
		{
			Yii::app()->user->setFlash('success', "Could not find the given Customer Box");
		}

		$this->redirect(array('customerBoxes','date'=>$CustBox->Box->delivery_date_id));
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
