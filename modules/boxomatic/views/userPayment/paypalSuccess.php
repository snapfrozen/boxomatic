<?php $User=User::model()->findByPk(Yii::app()->user->id) ?>
<h1>Payment successful</h1>
<p>Credit will be added to your account shortly once we have received a notification from PayPal. 
	If you do not see your credit appear within 24 hours please contact us at 
	<a href="mailto:<?php echo SnapUtil::config('boxomatic/adminEmail') ?>"><?php echo SnapUtil::config('boxomatic/adminEmail') ?></a> mentioning your 
	<?php echo Yii::app()->name ?> ID: <strong><?php echo $User->bfb_id ?></strong> the date of the payment
	and the amount.</p>
