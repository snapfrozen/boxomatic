<?php

class SiteController extends Controller
{
	
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
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
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
		$model=new User;
		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			//Password is encrypted afterValidate
			//$model->password=Yii::app()->snap->encrypt($_POST['User']['password']);
			//$model->password_repeat=Yii::app()->snap->encrypt($_POST['User']['password_repeat']);
			
			if($model->save())
			{
				$Customer=new Customer();
				$Customer->attributes=$_POST['Customer'];
				$Customer->save();
				
				$model->customer_id = $Customer->customer_id;
				$model->update(array('customer_id'));
				
				$Auth = Yii::app()->authManager;
				$Auth->assign('customer',$model->id);
				
				//Send email
				$message = new YiiMailMessage('Welcome to Bellofoodbox');
				$message->view = 'welcome';
				$message->setBody(array('User'=>$model,'newPassword'=>$_POST['User']['password']), 'text/html');
				$message->addTo('info@bellofoodbox.org.au');
				$message->addTo($model->user_email);
				$message->setFrom(array(Yii::app()->params['adminEmail'] => Yii::app()->params['adminEmailFromName']));
				
				if(!@Yii::app()->mail->send($message))
				{
					$mailError=true;
				}
				
				$identity=new UserIdentity($model->user_email, $_POST['User']['password']);
				$identity->authenticate();

				Yii::app()->user->login($identity);
				User::model()->updateByPk($identity->id, array('last_login_time'=>new CDbExpression('NOW()')));
				
				$this->redirect(array('customer/welcome'));
			}
		}
		$model->password='';
		$model->password_repeat='';
		$this->render('register',array('model'=>$model));
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
//		$sql=
//			'SELECT cb.customer_box_id, w.week_delivery_date, cw.week_id, cb.customer_id, b.box_price, cb.delivery_cost, l.location_delivery_value, l.is_pickup
//			FROM `customer_boxes` cb
//			INNER JOIN boxes b ON cb.box_id = b.box_id
//			INNER JOIN customer_weeks cw ON b.week_id = cw.week_id
//			INNER JOIN locations l ON cw.customer_location_id = l.location_id
//			INNER JOIN weeks w ON b.week_id = w.week_id
//			WHERE delivery_cost != location_delivery_value
//			AND week_delivery_date > NOW()';
		
//		$connection=Yii::app()->db; 
//		$dataReader=$connection->createCommand($sql)->query();
//		foreach($dataReader as $row)
//		{
//			$custBoxId=$row['customer_box_id'];
//			$delivery=$row['location_delivery_value'];
//			$upSql="UPDATE customer_box SET delivery_cost=$delivery WHERE customer_box_id=$custBoxId;";
//			echo $upSql.'<br />';
//			//$connection->createCommand($upSql)->execute();
//		}
		
		$CustBoxes=CustomerBox::model()->with(array('Box'=>array('Week')))->findAll('Week.week_delivery_date > NOW()');
		$n=0;
		foreach($CustBoxes as $CustBox)
		{
			$CustWeek=CustomerWeek::model()->findByAttributes(array('customer_id'=>$CustBox->customer_id, 'week_id'=>$CustBox->Box->week_id));
			if($CustBox->delivery_cost != $CustWeek->Location->location_delivery_value)
			{
				$User=$CustBox->Customer->User;
				echo "<p>Customer $User->id: {$User->full_name} ($CustBox->delivery_cost - {$CustWeek->Location->location_delivery_value})</p>";
				$upSql="UPDATE customer_box SET delivery_cost={$CustWeek->Location->location_delivery_value} WHERE customer_box_id=$CustBox->customer_box_id;";
				$n++;
			}
		}
		echo "<p><strong>count: $n</strong></p>";
	}
	
	public function actionGenerateLocations()
	{
	
		/*
		$CustWeeks=CustomerWeek::model()->findAll();
		foreach($CustWeeks as $CustWeek)
		{
			$CustWeek->location_id=$CustWeek->Customer->location_id;
			$CustWeek->customer_location_id=$CustWeek->Customer->customer_location_id;
			$CustWeek->save();
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
			$cust=$row['customer_id'];
			$upSql="UPDATE customers SET location_id=$loc WHERE customer_id=$cust;";
			$connection->createCommand($upSql)->execute();
		}
		
		$Customers=Customer::model()->with(array('Location','User'))->findAll('Location.is_pickup=0');
		
		foreach($Customers as $Customer) 
		{
			if($Customer->User)
			{
				$User=$Customer->User;
				$CustLoc=new CustomerLocation;
				$CustLoc->customer_id=$Customer->customer_id;
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
				
				echo '<p>Customer:' . $Customer->customer_id . '</p>';
			}
			else
				echo '<p>No User for Customer:' . $Customer->customer_id . '</p>';
		}
		*/
		exit;
	}
	
	/*
	public function actionSendWelcomeEmails()
	{
		$Users=User::model()->findAll('customer_id is not null');

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
