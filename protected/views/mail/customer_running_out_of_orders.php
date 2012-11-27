<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Running out of orders</title>
</head>
<body style="margin:0;">
<p>Hi <?php echo $Customer->User->first_name ?>,</p>
<p>After your current order, you currently have no future orders placed. 
	Please <?php echo CHtml::link('click here',array('CustomerBox/order','key'=>$User->auto_login_key)); ?>  to add more orders.</p>

<p>
--<br />
Kind regards,<br />
Box-o-matic, Box Processing System</p>

<p>Bellofoodbox is a community initiative promoting the local 
economy, encouraging sustainable agricultural practices
 and contributing to a fair, connected and resilient community. 
Distributing local, seasonal, affordable, healthy, fresh food that 
is accessible to Bellingen, Dorrigo, Urunga, and Coffs Harbour areas.</p>

<p>Thank you for your generous support and being part of this wonderful project.</p>

<p>Email: <a href="mailto:info@bellofoodbox.org.au">info@bellofoodbox.org.au</a></p>

</body>
</html>