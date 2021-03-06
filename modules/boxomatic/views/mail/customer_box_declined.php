<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Oh No! Your order has been declined.</title>
</head>
<body style="margin:0;">
<p>Hi <?php echo $Customer->first_name ?>,</p>

<p>Unfortunately your <?php echo Yii::app()->name ?> order for 
pick up on <?php echo $UserBox->Box->DeliveryDate->date ?> has been DECLINED, as you 
have insufficient <?php echo Yii::app()->name ?> credit.</p>

<p>Please contact us immediately if you feel this is an error.</p>

<h3>Topping up your account</h3>

<p>Please top up your <?php echo Yii::app()->name ?> account to 
ensure you receive a delicious <?php echo Yii::app()->name ?>, 
full of beautiful fresh, local produce <strong>next week</strong>.</p>

<ul>
  <li><strong>Online:</strong> You can top up your balance directly using PayPal at <a href="<?php echo $this->createAbsoluteUrl('/') ?>"><?php echo $this->createAbsoluteUrl('/') ?></a></li>
  <li><strong>Direct Deposit:</strong> BSB: 082469, ACC: 144056759, ACC Name: Bellingen Green Grocers Pty Ltd.<br />
  When depositing money via bank transfer, please use your <?php echo Yii::app()->name ?> ID as your reference.<br />
  Your <?php echo Yii::app()->name ?> ID is: <strong><?php echo $Customer->bfb_id; ?></strong>.</li>
  <li><strong>Cash:</strong> In store at Bellingen Greengrocers, Cnr Hyde & Church Sts, Bellingen, until the Wednesday before your order.</p></li>
</ul>

<p>
--<br />
Kind regards,<br />
Box-O-Matic, Box Processing System</p>


<p>Thank you for your generous support and being part of this wonderful project.</p>

<p>Email: <a href="<?php echo SnapUtil::config('boxomatic/adminEmail') ?>"><?php echo SnapUtil::config('boxomatic/adminEmail') ?></a></p>
</body>
</html>

