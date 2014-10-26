<?php

class UserPaymentController extends BoxomaticController
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
				'roles'=>array('customer','admin'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','enterPayments'),
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
		$model=new UserPayment;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST['UserPayment']))
		{
			$model->attributes=$_POST['UserPayment'];
			$model->user_id=Yii::app()->user->user_id;
			$model->payment_date=new CDbExpression('NOW()');
			
			if($model->save())
			{
				$Customer=$model->Customer;
				$validator=new CEmailValidator();
				if($validator->validateValue($Customer->User->email)) 
				{
					//email payment receipt
					$adminEmail = SnapUtil::config('boxomatic/adminEmail');
					$adminEmailFromName = SnapUtil::config('boxomatic/adminEmailFromName');
					$message = new YiiMailMessage('Payment receipt');
					$message->view = 'customer_payment_receipt';
					$message->setBody(array('Customer'=>$Customer, 'UserPayment' => $model), 'text/html');
					$message->addTo($Customer->User->email);
					$message->addTo($adminEmail);
					$message->setFrom(array($adminEmail => $adminEmailFromName));

					if(!@Yii::app()->mail->send($message))
					{
						$mailError=true;
					}
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

		if(isset($_POST['UserPayment']))
		{
			$model->attributes=$_POST['UserPayment'];
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
		$dataProvider=new CActiveDataProvider('UserPayment');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new UserPayment('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['UserPayment']))
			$model->attributes=$_GET['UserPayment'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Allow admins to enter payments on behalf of customers.
	 */
	public function actionEnterPayments()
	{	
		$model=new UserPayment;
		$model->payment_date=date('Y-m-d H:i:s');

		if(isset($_POST['UserPayment']))
		{
			$model->attributes=$_POST['UserPayment'];
			$model->staff_id=Yii::app()->user->id;
			if($model->save())
			{
			    $Customer=$model->User;
				$validator=new CEmailValidator();
				if($validator->validateValue($Customer->email)) 
				{
					//email payment receipt			    
					$message = new YiiMailMessage('Payment receipt');
					$message->view = 'customer_payment_receipt';
					$message->setBody(array('Customer'=>$Customer, 'UserPayment' => $model), 'text/html');
					$message->addTo($Customer->email);
					$message->addTo(SnapUtil::config('boxomatic/adminEmail'));
					$message->setFrom(array(SnapUtil::config('boxomatic/adminEmail') => SnapUtil::config('boxomatic/adminEmailFromName')));

					if(!@Yii::app()->mail->send($message))
					{
						$mailError=true;
					}
				}
				$this->refresh();
		    }
		}
		
		$search_model=new UserPayment('search');
		$search_model->unsetAttributes();  // clear any default values
		if(isset($_GET['UserPayment']))
			$search_model->attributes=$_GET['UserPayment'];


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
		$model=UserPayment::model()->findByPk($id);
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
