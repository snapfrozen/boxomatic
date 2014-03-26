<?php $User=User::model()->findByPk(Yii::app()->user->id) ?>
<h1>Payment successful</h1>
<p>Credit will be added to your account shortly once we have received a notification from PayPal. 
	If you do not see your credit appear within 24 hours please contact us at 
	<a href="mailto:info@bellofoodbox.org.au">info@bellofoodbox.org.au</a> mentioning your 
	Bellofoodbox ID: <strong><?php echo $User->bfb_id ?></strong> the date of the payment
	and the amount.</p>
