<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Running out of orders</title>
</head>
<body style="margin:0;">
<p>Hi <?php echo $Customer->User->first_name ?>,</p>
<p>You are running out of orders. Please <?php echo CHtml::link('click here',array('CustomerBox/order','key'=>$User->auto_login_key)); ?> to update your orders</p>

</body>
</html>
