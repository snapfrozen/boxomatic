<?php

class CustomerPaymentController extends Controller
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
				'actions'=>array('index','view','create','paypalFailure','paypalSuccess'),
				'roles'=>array('customer'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','enterPayments'),
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
		$model=new CustomerPayment;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST['amount']))
		{
			$ipn = new PPIpnAction($this,"ipn");

			echo 'yes';exit;
			/*
			* Process payment
			*
			* See PPPhpTransaction for validation details, important values:
			* - PPPhpTransaction::currency = Valid currency (default: USD)
			* - PPPhpTransaction::amount = Minumum payment amount (default: 5.00)
			*
			* I recommend using an active record for storage / validation.
			*/ 
			$ipn->onRequest = function($event) {
				// Check if this is a transaction
				if (!isset($event->details["txn_id"])) {
					$event->msg = "Missing txn_id";
					Yii::log($event->msg,"warning","payPal.controllers.DefaultController");
					$event->sender->onFailure($event);
					return;
				}

				// Put payment details into a transaction model
				$transaction = new PPPhpTransaction;
				$transaction->paymentStatus = $event->details["payment_status"];
				$transaction->mcCurrency = $event->details["mc_currency"];
				$transaction->mcGross = $event->details["mc_gross"];
				$transaction->receiverEmail = $event->details["receiver_email"];
				$transaction->txnId = $event->details["txn_id"];

				// Failed to process payment: Log and invoke failure event
				if (!$transaction->save()) {
					$event->msg = "Could not process payment";
					Yii::log("{$event->msg}\nTransaction ID: {$event->details["txn_id"]}", "error",
							"payPal.controllers.DefaultController");
					$event->sender->onFailure($event);
				}

				// Successfully processed payment: Log and invoke success event
				else if ($transaction->save()) {
					$event->msg = "Successfully processed payment";
					Yii::log("{$event->msg}\nTransaction ID: {$event->details["txn_id"]}", "info",
							"payPal.controllers.DefaultController");
					$event->sender->onSuccess($event);
				}
			};

			// Ignoring failures
			$ipn->onFailure = function($event) {
				echo 'failure';exit;
				// Could e.g. send a notification mail on certain events
			};

			// Send confirmation mail to customer
			$ipn->onSuccess = function($event) {
				echo 'success';exit;
//				$to = $event->details["payer_email"];
//				$from = $event->details["receiver_email"];
//				$subject = "Payment received";
//				$body = "Your payment has been processed.\n" .
//					"Receiver: $from\n" .
//					"Amount: {$event->details["mc_gross"]} {$event->details["mc_amount"]}\n";
//				$headers="From: $from\r\nReply-To: $from";
//				mail($to,$subject,$body,$headers);
			};

			$ipn->run();
		}
		
		if(isset($_POST['CustomerPayment']))
		{
			$model->attributes=$_POST['CustomerPayment'];
			$model->customer_id=Yii::app()->user->customer_id;
			$model->payment_date=new CDbExpression('NOW()');
			
			if($model->save())
			{
			    $Customer=$model->Customer;
			    //email payment receipt			    
			    $message = new YiiMailMessage('Payment receipt');
				$message->view = 'customer_payment_receipt';
				$message->setBody(array('Customer'=>$Customer, 'CustomerPayment' => $model), 'text/html');
				$message->addTo($Customer->User->user_email);
				$message->addTo('info@bellofoodbox.org.au');
				$message->setFrom(array(Yii::app()->params['adminEmail'] => Yii::app()->params['adminEmailFromName']));
				
				if(!@Yii::app()->mail->send($message))
				{
					$mailError=true;
				}
				$this->redirect(array('view','id'=>$model->payment_id));
			}
		}

		$User=User::model()->findByPk(Yii::app()->user->id);
		$this->render('create',array(
			'model'=>$model,
			'User'=>$User,
			'Customer'=>$User->Customer,
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

		if(isset($_POST['CustomerPayment']))
		{
			$model->attributes=$_POST['CustomerPayment'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->payment_id));
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
		$dataProvider=new CActiveDataProvider('CustomerPayment');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new CustomerPayment('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CustomerPayment']))
			$model->attributes=$_GET['CustomerPayment'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Allow admins to enter payments on behalf of customers.
	 */
	public function actionEnterPayments()
	{	
		$model=new CustomerPayment;
		
		if(isset($_POST['CustomerPayment']))
		{
			$model->attributes=$_POST['CustomerPayment'];
			$model->staff_id=Yii::app()->user->id;
			if($model->save())
			{
			    $Customer=$model->Customer;
			    //email payment receipt			    
			    $message = new YiiMailMessage('Payment receipt');
				$message->view = 'customer_payment_receipt';
				$message->setBody(array('Customer'=>$Customer, 'CustomerPayment' => $model), 'text/html');
				$message->addTo($Customer->User->user_email);
				$message->addTo('info@bellofoodbox.org.au');
				$message->setFrom(array(Yii::app()->params['adminEmail'] => Yii::app()->params['adminEmailFromName']));
				
				if(!@Yii::app()->mail->send($message))
				{
					$mailError=true;
				}

				$this->redirect(array('view','id'=>$model->payment_id));
		    }
		}
		
		$search_model=new CustomerPayment('search');
		$search_model->unsetAttributes();  // clear any default values
		if(isset($_GET['CustomerPayment']))
			$search_model->attributes=$_GET['CustomerPayment'];


		$this->render('enterPayments',array(
			'model'=>$model, 'search_model'=>$search_model
		));
	}
	
	public function actionPaypalFailure()
	{
		$this->render('paypalFailure');
	}
	
	public function actionPaypalSuccess()
	{
		$this->render('paypalSuccess');
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=CustomerPayment::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='customer-payment-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
