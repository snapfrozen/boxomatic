<?php

class CronController extends Controller
{

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
	 * Generate new weeks and boxes for each week
	 */
	public function actionCreateFutureWeeksAndBoxes()
	{
		$weeksInAdvance=Yii::app()->params['autoCreateWeeks'];
		
		$latestWeek=Week::getLastEnteredWeek();
		$latestDate=strtotime($latestWeek->week_delivery_date);
		$targetDate=strtotime('+' . $weeksInAdvance . ' weeks');

		$BoxSizes=BoxSize::model()->findAll();
		
		while($latestDate <= $targetDate) 
		{
			$latestDateStr=date('Y-m-d',$latestDate);
			$latestDate=strtotime($latestDateStr . ' +1 week');
			$newDateStr=date('Y-m-d', $latestDate);			
			
			$Week=new Week;
			$Week->week_delivery_date=$newDateStr;
			$Week->save();
			
			foreach($BoxSizes as $BoxSize)
			{
				$Box=new Box;
				$Box->size_id=$BoxSize->box_size_id;
				$Box->box_price=$BoxSize->box_size_price;
				$Box->week_id=$Week->week_id;
				$Box->save();
			}
			
			echo '<p>Created new week: ' . $Week->week_delivery_date . '</p>';
		}
		
		echo '<p><strong>Finished.</strong></p>';
		
		Yii::app()->end();
	}
	
	
	/**
	 * Send reminder emails to those who haven't paid for their next week's box
	 */
	public function actionSendReminderEmails()
	{
		$Customers=Customer::model()->findAllWithNoOrders();
		foreach($Customers as $Cust)
		{
			$validator=new CEmailValidator();
			if($validator->validateValue(trim($Cust->User->user_email))) 
			{
				$User=$Cust->User;
				echo '<p>Will send reminder email to: "'.$User->user_email.'"</p>';
				/*
				$User->auto_login_key=$User->generatePassword(50,4);
				$User->update_time=new CDbExpression('NOW()');
				$User->update();
				
				//email payment receipt			    
				$message = new YiiMailMessage('Running out of orders');
				$message->view = 'customer_running_out_of_orders';
				$message->setBody(array('Customer'=>$Cust,'User'=>$User), 'text/html');
				//$message->addTo($Cust->User->user_email);
				$message->addTo('info@bellofoodbox.org.au');
				$message->setFrom(array(Yii::app()->params['adminEmail'] => Yii::app()->params['adminEmailFromName']));

				if(!@Yii::app()->mail->send($message)) {
					echo '<p style="color:red"><strong>Email failed sending to: ' . $Cust->User->user_email . '</strong></p>';
				} else {
					echo '<p>Running out of orders message sent to: ' . $Cust->User->user_email . '</p>';
				}
				 */
			}
			else
			{
				echo '<p style="color:red"><strong>Email not valid: "' . $Cust->User->user_email . '"</strong></p>';
			}
		}
		
		echo '<p><strong>Finished.</strong></p>';
		//Yii::app()->end();
	}
	
}