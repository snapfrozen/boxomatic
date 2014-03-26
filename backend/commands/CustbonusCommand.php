<?php
class CustbonusCommand extends CConsoleCommand
{
	public function getHelp()
	{
		return <<<EOD
USAGE
custbonus [option]
OPTIONS
    credit - credit $5 to all customers
    debit [date] - debit $5 from all customers who didn't place an order in the past week.
	    date - date that you want to test from which a transaction has been made. (strtotime formatted string)
	email
DESCRIPTION
Credit or debit to and from customers
EOD;
	
	}
	/**
	* Execute the action.
	* @param array command line parameters specific for this command
	*/
	public function run($args)
	{
		if(in_array('credit',$args))
		{
			$Customers=Customer::model()->findAll();
			foreach($Customers as $Customer) 
			{	
				$CustPayment=new UserPayment;
				$CustPayment->payment_value=5;
				$CustPayment->payment_type='BONUS';
				$CustPayment->staff_id=1;
				$CustPayment->payment_note='February Love';
				$CustPayment->customer_id=$Customer->customer_id;
				$CustPayment->payment_date=new CDbExpression('NOW()');
				$CustPayment->save();
			}
			echo "\n".count($Customers).' rows affected';
		}
		
		if(in_array('debit',$args))
		{
			if(!isset($args[1]))
			{
				echo "\n".'Date needed';
				echo $this->getHelp();
				exit;
			}
			$date=strtotime($args[1]);
			$isoDate=date('Y-m-d h:i:s',$date);
			$confirm=$this->prompt('You are about to debit $5 from every account that hasn\'t made a transaction since '.$isoDate.' continue? y/n');
			if($confirm=='y')
			{
				$Customers=Customer::model()->findAllBySql('
SELECT * FROM customers WHERE customer_id not in (
	SELECT DISTINCT c.customer_id FROM customers c 
		JOIN customer_payments cp ON c.customer_id=cp.customer_id
		WHERE payment_date > "'.$isoDate.'" 
	AND payment_type = \'DEBIT\' 
	AND payment_value <= -20 )
');
				foreach($Customers as $Customer)
				{
					$CustPayment=new UserPayment;
					$CustPayment->payment_value=-5;
					$CustPayment->payment_type='BONUS REVERSAL';
					$CustPayment->staff_id=1;
					$CustPayment->payment_note='February Love';
					$CustPayment->customer_id=$Customer->customer_id;
					$CustPayment->payment_date=new CDbExpression('NOW()');
					$CustPayment->save();
				}
				echo "\n".count($Customers).' rows affected';
			}
			else 
			{
				echo 'no action taken'."\n";
			}
		}
		
		if(in_array('email',$args))
		{
			$Customers=Customer::model()->resetScope()->findAll();
			foreach($Customers as $Customer)
			{
				$User=$Customer->User;
				if(!$User) 
					continue;
				
				$User->resetScope();
				$message=new YiiMailMessage('You have recieved Bellofoodbox Love Credit!');
				$message->view = 'bonus';
				$message->setBody(array('User'=>$User), 'text/html');
				$email=trim($User->email);
				
				$validator=new CEmailValidator();
				if($validator->validateValue($email)) 
				{
					//$message->addTo('donovan@snapfrozen.com.au');
					//$message->addTo('leigh@bellofoodbox.org.au');
					$message->setFrom(array(Yii::app()->params['adminEmail'] => Yii::app()->params['adminEmailFromName']));
					$message->addTo($email);
					if(!@Yii::app()->mail->send($message))
					{
						echo 'Could not send email to user:'.$User->id;
					}
					else
					{
						echo 'Email sent to user:'.$User->id;
					}
				}
				else
				{
					echo 'Not a valid email address for user:'.$User->id;
				}
				echo "\n";
			}
		}
		echo "\n";
	}
}