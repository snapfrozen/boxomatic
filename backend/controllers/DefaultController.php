<?php

class DefaultController extends BoxomaticController
{
	const HOMEPAGE_ID = 4;
	public $Content = null;
	
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
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('index','error','contact','register','login','logout','captcha'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
		/*
		$Content=Content::model()->findByPk(self::HOMEPAGE_ID);
		$this->Content = $Content;
		
		$this->layout = '//layouts/column1';
		$view = '/content/view';
		
		if($this->getLayoutFile('//layouts/content_types/'.$Content->type))
			$this->layout = '//layouts/content_types/'.$Content->type;

		if($this->getViewFile('/content/content_types/'.$Content->type))
			$view = '/content/content_types/'.$Content->type;		
		
		$this->render($view,array(
			'Content'=>$Content,
		));
		 *
		 */
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
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}
	
	/**
	 * Displays the register page
	 */
	public function actionRegister()
	{
		$model = new User;
		$vars = array();

		if(isset($_POST['User']))
		{
			$model->attributes = $_POST['User'];
			$model->scenario = 'register';

			if($model->save())
			{
				$Customer=new Customer();
				$Customer->attributes=$_POST['Customer'];
				$Customer->save();
				
				if(!$Customer->Location->is_pickup)
				{
					$CustLoc=new UserLocation;
					$CustLoc->user_id=$Customer->user_id;
					$CustLoc->location_id=$Customer->location_id;
					$CustLoc->address=$model->user_address;
					$CustLoc->address2=$model->user_address2;
					$CustLoc->suburb=$model->user_suburb;
					$CustLoc->state=$model->user_state;
					$CustLoc->postcode=$model->user_postcode;
					$CustLoc->phone=!empty($model->user_phone)?$model->user_phone:$model->user_mobile;
					$CustLoc->save(false);
				}

				$model->user_id = $Customer->user_id;
				$model->update(array('user_id'));

				$Auth = Yii::app()->authManager;
				$Auth->assign('customer',$model->id);

				//Send email
				$message = new YiiMailMessage('Welcome to ' . Yii::app()->name);
				$message->view = 'welcome';
				$message->setBody(array('User'=>$model,'newPassword'=>$_POST['User']['password']), 'text/html');
				$message->addTo(Yii::app()->params['adminEmail']);
				$message->addTo($model->email);
				$message->setFrom(array(Yii::app()->params['adminEmail'] => Yii::app()->params['adminEmailFromName']));

				if(!@Yii::app()->mail->send($message)) {
					$mailError=true;
				}

				$identity=new UserIdentity($model->email, $_POST['User']['password']);
				$identity->authenticate();

				Yii::app()->user->login($identity);
				User::model()->updateByPk($identity->id, array('last_login_time'=>new CDbExpression('NOW()')));

				$this->redirect(array('customer/welcome'));
			}
		}

		$model->password='';
		$model->password_repeat='';
		$vars['model'] = $model;
		
		// $this->render('register',array('model'=>$model));
		$this->render('register', $vars);
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
	
	public function actionUpdateDeliveryCosts()
	{
		/*
		$sql=
			'SELECT cb.user_box_id, w.date, cw.week_id, cb.user_id, b.box_price, cb.delivery_cost, l.location_delivery_value, l.is_pickup
			FROM `user_boxes` cb
			INNER JOIN boxes b ON cb.box_id = b.box_id
			INNER JOIN customer_weeks cw ON b.week_id = cw.week_id
			INNER JOIN locations l ON cw.customer_location_id = l.location_id
			INNER JOIN weeks w ON b.week_id = w.week_id
			WHERE delivery_cost != location_delivery_value
			AND date > NOW()';
		
		$connection=Yii::app()->db; 
		$dataReader=$connection->createCommand($sql)->query();
		foreach($dataReader as $row)
		{
			$custBoxId=$row['user_box_id'];
			$delivery=$row['location_delivery_value'];
			$upSql="UPDATE customer_box SET delivery_cost=$delivery WHERE user_box_id=$custBoxId;";
			echo $upSql.'<br />';
			//$connection->createCommand($upSql)->execute();
		}
		 */
		
		$CustBoxes=UserBox::model()->with(array('Box'=>array('with'=>array('DeliveryDate'))))->findAll('DeliveryDate.date > NOW()');
		$n=0;
		foreach($CustBoxes as $CustBox)
		{
			$CustDeliveryDate=Order::model()->findByAttributes(array('user_id'=>$CustBox->user_id, 'delivery_date_id'=>$CustBox->Box->delivery_date_id));
			if($CustBox->delivery_cost != $CustDeliveryDate->Location->location_delivery_value)
			{
				$User=$CustBox->Customer->User;
				echo "<p>Customer $User->id: {$User->full_name} ($CustBox->delivery_cost - {$CustDeliveryDate->Location->location_delivery_value})</p>";
				$upSql="UPDATE customer_box SET delivery_cost={$CustDeliveryDate->Location->location_delivery_value} WHERE user_box_id=$CustBox->user_box_id;";
				$n++;
			}
		}
		echo "<p><strong>count: $n</strong></p>";
	}
	
	public function actionGenerateLocations()
	{
	
		/*
		$CustDeliveryDates=Order::model()->findAll();
		foreach($CustDeliveryDates as $CustDeliveryDate)
		{
			$CustDeliveryDate->location_id=$CustDeliveryDate->Customer->location_id;
			$CustDeliveryDate->customer_location_id=$CustDeliveryDate->Customer->customer_location_id;
			$CustDeliveryDate->save();
		}
		*/
		
		//$Customers=Customer::model()->with(array('Location','User'))->findAll('Location.is_pickup=0');
		/*
		$connection=Yii::app()->db; 
		$sql="SELECT * FROM customers_tmp";
		$dataReader=$connection->createCommand($sql)->query();
		foreach($dataReader as $row)
		{
			$loc=$row['location_id'];
			$cust=$row['user_id'];
			$upSql="UPDATE customers SET location_id=$loc WHERE user_id=$cust;";
			$connection->createCommand($upSql)->execute();
		}
		
		$Customers=Customer::model()->with(array('Location','User'))->findAll('Location.is_pickup=0');
		
		foreach($Customers as $Customer) 
		{
			if($Customer->User)
			{
				$User=$Customer->User;
				$CustLoc=new UserLocation;
				$CustLoc->user_id=$Customer->user_id;
				$CustLoc->location_id=$Customer->location_id;
				$CustLoc->address=$User->user_address;
				$CustLoc->address2=$User->user_address2;
				$CustLoc->suburb=$User->user_suburb;
				$CustLoc->state=$User->user_state;
				$CustLoc->postcode=$User->user_postcode;
				$CustLoc->phone=$User->user_phone;
				$CustLoc->status=1;
				if(!$CustLoc->save(false))
					print_r($CustLoc->getErrors());
				
				$Customer->customer_location_id=$CustLoc->customer_location_id;
				if(!$Customer->save(false))
					print_r($Customer->getErrors());
				
				echo '<p>Customer:' . $Customer->user_id . '</p>';
			}
			else
				echo '<p>No User for Customer:' . $Customer->user_id . '</p>';
		}
		*/
		exit;
	}
	
	/*
	public function actionSendWelcomeEmails()
	{
		$Users=User::model()->findAll('user_id is not null');

		foreach($Users as $User)
		{
			$User->resetPasswordAndSendWelcomeEmail();
		}
		
	}
	 */
	
	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}
