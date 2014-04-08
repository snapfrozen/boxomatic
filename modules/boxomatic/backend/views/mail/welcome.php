<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Welcome to <?php echo Yii::app()->name ?></title>
</head>
<body style="margin:0;">
	<p>Hi <?php echo $User->first_name ?>,</p>

	<p>Thank you for joining the <?php echo Yii::app()->name ?> project.
	We look forward to supplying you with lots of 
	fresh, local seasonal produce.</p>

	<p>Your login details are:</p>
	<p>Username: <b><?php echo $User->email ?></b><br />
	Password: <b><?php echo $newPassword ?></b></p>
	
	<p>You can order boxes and check your account status at <a href="<?php echo $this->createAbsoluteUrl('/') ?>"><?php echo $this->createAbsoluteUrl('/') ?></a></p>

<p>When depositing money via bank transfer, please use your <?php echo Yii::app()->name ?> ID as your bank reference.<br />
Your <?php echo Yii::app()->name ?> ID is: <strong><?php echo $User->bfb_id; ?></strong>.

	<p>Each time you make a payment, we will send an email
	confirming receipt of payment.</p>
	
	<p>For each week you have an order placed, you'll receive a confirmation email, if there is enough credit in your <?php echo Yii::app()->name ?> account.</p>
		
	<p>You can top up your <?php echo Yii::app()->name ?> account at anytime within the <?php echo Yii::app()->name ?> system using PayPal, or by direct bank transfer.</p> 
	<p>Box payments are only deducted from your <?php echo Yii::app()->name ?> account each week, as 
	your order is processed.</p>

	<p>If you have placed an order and we do not receive 
	your payment by 9:30am Thursday, we will send you 
	an email stating that your order has been declined 
	due to insufficient credit.</p>
	
	<p>You can place as many orders as you like into the future up to six months in advance. However, we'll only pack your box if you have enough credit in your account.</p>

	<p>	
--<br />
Kind regards,<br />
Box-O-Matic, Box Processing System</p>

<p><?php echo Yii::app()->name ?> is run by Bellingen Greengrocers and encourages sustainable agricultural practices and supports local economy and community, distributing local, seasonal, affordable, healthy, fresh food that is accessible to Bellingen, Dorrigo, Urunga, Valla and Coffs Harbour areas.</p>

<p>Thank you for your generous support and being part of this wonderful project.</p>

<p>Email: <a href="mailto:<?php echo SnapUtil::config('boxomatic/adminEmail') ?>"><?php echo SnapUtil::config('boxomatic/adminEmail') ?></a></p>	
</body>
</html>
