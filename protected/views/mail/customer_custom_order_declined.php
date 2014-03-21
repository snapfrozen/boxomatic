<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Oh No! Your order has been declined.</title>
</head>
<body style="margin:0;">
<p>Hi <?php echo $Customer->User->first_name ?>,</p>

<p>Unfortunately your <?php echo Yii::app()->name ?> order for 
pick up on <?php echo $CDD->DeliveryDate->date ?> has been DECLINED, as you 
have insufficient <?php echo Yii::app()->name ?> credit.</p>

<p>Please contact us immediately if you feel this is an error.</p>

<h3>Topping up your account</h3>

<p>Please top up your <?php echo Yii::app()->name ?> account to 
ensure you receive a delicious <?php echo Yii::app()->name ?>, 
full of beautiful fresh, local produce <strong>next week</strong>.</p>
</body>
</html>

