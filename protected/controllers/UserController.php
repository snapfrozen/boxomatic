<?php

class UserController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';

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
		);
	}
	
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
				'actions'=>array('passwordReset','forgottenPassword','captcha'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('update','view','loginAs','changePassword','dontWant'),
				'roles'=>array('customer','admin'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('index', 'admin','delete','create','customers','resetPassword'),
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
		$model = $this->loadModel($id);
		if(!Yii::app()->user->checkAccess('Admin') && $model->id != Yii::app()->user->id) {
			throw new CHttpException(403,'Access Denied.');
		}
		$this->render('view',array(
			'model'=>$model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new User;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST['User']))
		{
			$model->scenario='changePassword';
			$model->attributes=$_POST['User'];
			if(isset($_POST['User']['password']))
				$model->password=$_POST['User']['password'];
			if($model->save()) 
			{
				if(isset($_POST['role'])) {
					$model->setRole($_POST['role']);
				}
				if(isset($_POST['role']) && $_POST['role'] == 'customer')
				{
					if(empty($model->customer_id)) {
						$Customer=new Customer();
					} else {
						$Customer=Customer::model()->findByPk($model->customer_id);
					}
					$Customer->attributes=$_POST['Customer'];
					$Customer->save();
					
					$CustLoc=new CustomerLocation;
					$CustLoc->customer_id=$Customer->customer_id;
					$CustLoc->location_id=$Customer->location_id;
					$CustLoc->address=$model->user_address;
					$CustLoc->address2=$model->user_address2;
					$CustLoc->suburb=$model->user_suburb;
					$CustLoc->state=$model->user_state;
					$CustLoc->postcode=$model->user_postcode;
					$CustLoc->phone=!empty($model->user_phone)?$model->user_phone:$model->user_mobile;
					$CustLoc->save();
					
					$model->customer_id = $Customer->customer_id;
					$model->update(array('customer_id'));
				}
				
				$this->redirect(array('view','id'=>$model->id));
			}
		}
		
		$custLocDataProvider=null;
		if($model->Customer)
		{
			$custLocDataProvider=new CActiveDataProvider('CustomerLocation');
		}

		$this->render('create',array(
			'model'=>$model,
			'custLocDataProvider'=>$custLocDataProvider
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
		
		if(isset($_POST['role']) && $_POST['role'] == 'customer')
		{
			if(empty($model->customer_id)) {
				$Customer=new Customer();
			} else {
				$Customer=Customer::model()->findByPk($model->customer_id);
			}
			$Customer->save(false);

			$CustLoc=new CustomerLocation;
			$CustLoc->customer_id=$Customer->customer_id;
			$CustLoc->location_id=$Customer->location_id;
			$CustLoc->address=$model->user_address;
			$CustLoc->address2=$model->user_address2;
			$CustLoc->suburb=$model->user_suburb;
			$CustLoc->state=$model->user_state;
			$CustLoc->postcode=$model->user_postcode;
			$CustLoc->phone=!empty($model->user_phone)?$model->user_phone:$model->user_mobile;
			$CustLoc->save();

			$model->customer_id = $Customer->customer_id;
			$model->update(array('customer_id'));
		}

		$allSaved=true;
		if(isset($_POST['Customer']))
		{
			$Customer=$model->Customer;
			$locationId=$_POST['Customer']['delivery_location_key'];
			$custLocationId=new CDbExpression('NULL');
			if(strpos($locationId,'-'))
			{ //has a customer location
				$parts=explode('-',$locationId);
				$locationId=$parts[1];
				$custLocationId=$parts[0];
			}

			$Customer->location_id=$locationId;
			$Customer->customer_location_id=$custLocationId;
			$Customer->save();
			
			$Customer->attributes=$_POST['Customer'];
			if(!$Customer->update())
				$allSaved=false;
				
			$Customer->updateOrderDeliveryLocations();
		}
		
		if(isset($_POST['Supplier']))
		{
			$Supplier=$model->Supplier;
			$Supplier->attributes=$_POST['Supplier'];
			if(!$Supplier->update())
				$allSaved=false;
		}
		
		if(isset($_POST['role'])) {
			$model->setRole($_POST['role']);
		}
		
		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			$model->validate();
			if(!$model->update())
				$allSaved=false;
			
			if($allSaved)
				$this->redirect(array('view','id'=>$model->id));
		}
		
		$custLocDataProvider=null;
		if($model->Customer)
		{
			$custLocDataProvider=new CActiveDataProvider('CustomerLocation',array(
				'criteria'=>array(
					'condition'=>'customer_id='.$model->customer_id
				)
			));
		}
		
		$this->render('update',array(
			'model'=>$model,
			'custLocDataProvider'=>$custLocDataProvider
		));
	}
	
	/**
	 * Change password page
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionChangePassword($id)
	{
		$model=$this->loadModel($id);
		$model->scenario='changePassword';
		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->validate()) 
			{
				$model->update();
				Yii::app()->user->setFlash('success', "Password updated.");
				$this->redirect(array('view','id'=>$model->id));
			}
			
		}
		$this->render('changePassword',array(
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
		$dataProvider=new CActiveDataProvider('User');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		$model->searchAdmin=true;
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Manages all models.
	 */
	public function actionCustomers()
	{
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('customers',array(
			'model'=>$model,
		));
	}
	
	/**
	 *  Login as a different user
	 */
	public function actionLoginAs($id)
	{
		if(Yii::app()->user->shadow_id || Yii::app()->user->checkAccess('Admin'))
		{
			$User=User::model()->resetScope()->findByPk((int)$id);
			$identity=new UserIdentity($User->user_email,'');
			$identity->loginAs($id, Yii::app()->user->id);
			$duration=3600*24*30; // 30 days
			Yii::app()->user->login($identity, $duration);
		}
		else
		{
			throw new CHttpException(404,'The requested page does not exist.');
		}
		
		if(Yii::app()->user->shadow_id) {
			$this->redirect(array('customerBox/order'));
		} else {
			$this->redirect(Yii::app()->user->shadow_referrer);
		}
	}
	
	/**
	 *  Reset password action performed by admin
	 */
	public function actionResetPassword($id)
	{
		$User=User::model()->findByPk($id);
		if($User->resetPasswordAndSendWelcomeEmail())
			Yii::app()->user->setFlash('success', "Password changed and email sent");
		else
			Yii::app()->user->setFlash('error', "Password changed but no email sent");

		$this->redirect(array('user/customers'));
	}
	
	/**
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionDontWant($id, $cat=null, $product=null, $like=null)
	{
		$model = $this->loadModel($id);
		if(!Yii::app()->user->checkAccess('Admin') && $model->id != Yii::app()->user->id) {
			throw new CHttpException(403,'Access Denied.');
		}
		
		if(!$cat)
			$cat = Category::productFeatureCategory;
		
		if($product) {
			$model->DontWant = array_merge($model->DontWant, array($product));
			$model->save();
			$model->refresh();
		}
		
		if($like) {
			$newArr = array();
			foreach($model->DontWant as $Product) {
				if($Product->id != $like) {
					$newArr[] = $Product->id;
				}
			}
			$model->DontWant = $newArr;
			$model->save();
			$model->refresh();
		}
		
		$Category = Category::model()->findByPk($cat);
		if($cat==Category::uncategorisedCategory) {
			$SupplierProducts = SupplierProduct::getUncategorised();
		} else {
			$SupplierProducts = $Category->SupplierProducts;
		}
		
		$dontWantIds = array();
		foreach($model->DontWant as $SP) {
			$dontWantIds[$SP->id] = $SP;
		}

		$this->render('dont_want',array(
			'model'=>$model,
			'curCat'=>$cat,
			'Category'=>$Category,
			'SupplierProducts'=>$SupplierProducts,
			'dontWantIds'=>$dontWantIds,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=User::model()->findByPk((int)$id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	/**
	 * Displays the forgotten password page
	 */
	public function actionForgottenPassword()
	{
		if( !Yii::app()->user->isGuest ) {
			$this->redirect(Yii::app()->homeUrl);
		}		
		
		$model=new ForgottenPasswordForm;
		$User=null;
		$mailError=false;
		
		// collect user input data
		if(isset($_POST['ForgottenPasswordForm']))
		{
			$model->scenario='changePassword';
			$model->attributes=$_POST['ForgottenPasswordForm'];
			
			// validate user input and redirect to the previous page if valid
			if($model->validate())
			{
				$User=$model->User;
				$User->password_retrieval_key=$User->generatePassword(50,4);
				$User->update_time=new CDbExpression('NOW()');
				$User->update();
				
				$message = new YiiMailMessage('FoodBox password renewal');
				$message->view = 'forgottenPassword';

				$url=$this->createAbsoluteUrl('user/passwordReset',array('p'=>$User->password_retrieval_key));
				
				//userModel is passed to the view
				$message->setBody(array('User'=>$User,'url'=>$url), 'text/html');

				$message->addTo($User->user_email);
				$message->setFrom(array(Yii::app()->params['adminEmail'] => Yii::app()->params['adminEmailFromName']));
				
				if(!@Yii::app()->mail->send($message))
				{
					$mailError=true;
				}
			}
		}
		
		// display the login form
		$this->render('forgottenPassword',array('model'=>$model,'User'=>$User,'mailError'=>$mailError));
	}
	
	/**
	 * Password reset form that the user is directed to in a password retrieval email
	 */
	public function actionPasswordReset($p)
	{
		$model=User::model()->findByAttributes( array('password_retrieval_key'=>$p), 'update_time > date_sub(NOW(), interval 1 hour)' );
		$updateComplete=false;
		
		if(isset($_POST['User']))
		{
			$model->scenario='changePassword';
			$model->attributes=$_POST['User'];
			$model->password=$_POST['User']['password'];
			if($model->validate()) 
			{
				//clear our key so it can't be used again.
				$model->password_retrieval_key = '';
				$model->update();
				
				$updateComplete=true;
			} 
		}
		
		$this->render('passwordReset',array(
			'model'=>$model,
			'updateComplete'=>$updateComplete,
		));
	}
}
