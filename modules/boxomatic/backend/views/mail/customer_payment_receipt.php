<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Payment Confirmation</title>
</head>
<body style="margin:0;">
<p>Hi <?php echo $Customer->first_name ?>,</p>

<p>Thank you for your Payment to <?php echo Yii::app()->name ?>.</p>

<h3><?php echo Yii::app()->name ?> payment details</h3>

<p>Name: <?php echo $Customer->first_name ?> <?php echo $Customer->last_name ?><br />
Date: <?php echo $UserPayment->payment_date ?><br />
Amount: <?php echo $UserPayment->payment_value ?><br />
Method: <?php echo $UserPayment->payment_type ?></p>

<p><strong>Your current balance is $<?php echo $Customer->balance ?></strong></p>

<p>This is NOT an order confirmation.
Details confirming any box orders will be sent 
in a separate email detailing pick up location and date.</p>

<h3>Managing your <?php echo Yii::app()->name ?> account</h3>

<p>You can check your account status, add credit and view previous 
orders at: 

<a href="<?php echo $this->createAbsoluteUrl('/') ?>"><?php echo $this->createAbsoluteUrl('/') ?>/</a></p>

<p>You are welcome to top up your account 
credit by making additional payments at anytime. 
Box payments are only deducted each week as 
your orders are processed.</p>

<p>When depositing money via bank transfer, please use your <?php echo Yii::app()->name ?> ID as your reference.<br />
Your <?php echo Yii::app()->name ?> ID is: <strong><?php echo $Customer->bfb_id; ?></strong>.

<p>
--<br />
Kind regards,<br />
Box-o-Matic, Box Processing System</p>

<p><?php echo Yii::app()->name ?> is run by Bellingen Greengrocers and encourages sustainable agricultural practices and supports local economy and community, distributing local, seasonal, affordable, healthy, fresh food that is accessible to Bellingen, Dorrigo, Urunga, Valla and Coffs Harbour areas.</p>

<p>Thank you for your generous support and being part of this wonderful project.</p>

<p>Email: <a href="mailto:<?php echo SnapUtil::config('boxomatic/adminEmail') ?>"><?php echo SnapUtil::config('boxomatic/adminEmail') ?></a></p>

</body>
</html>
