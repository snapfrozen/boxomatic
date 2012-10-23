<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Welcome to Bello FoodBox</title>
</head>
<body style="margin:0;">
	<p>Hello <?php echo $User->first_name ?>,</p>

	<p>Thank you for joining the Bellofoodbox project.
	We look forward to supplying you with lots of 
	fresh, local seasonal produce in the future.</p>

	<p>Your login details are:</p>
	<p>Username:<b><?php echo $User->user_email ?></b><br />
	Password:<b><?php echo $newPassword ?></b></p>
	<p>and your Bellofoodbox ID is <?php echo $User->bfb_id; ?></p>

	<p>Your Bellofoodbox ID is to be used when making 
	deposits and contacting us.</p>

	<p>Each time you make a payment, we will send an email
	confirming receipt of payment into our account.</p>

	<p>Each week you place an order, you will receive an
	email from us confirming your order, date and location
	of pick up, if you have sufficient credit* in your account.
	*You are welcome to top up your account 
	credit by making additional payments at anytime. 
	Box payments are only deducted each week as 
	your order is processed.</p>

	<p>If you have placed an order and we do not receive 
	your payment by Midnight Wednesday, we will send you 
	an email stating that your order has been Declined 
	due to insufficient credit.</p>


	<p>You can check your account status and previous 
	orders using your Bellofoodbox ID online at 
	<a href="http://www.bellofoodbox.org.au/order">www.bellofoodbox.org.au/order</a></p>


	<p>If you would like more information about 
	Bellofoodbox, please contact us;</p>

	<p>Email: info@bellofoodbox.org.au  <br />
	Phone: 0400 146 085</p>

	<p>&nbsp;</p>

	<p>Bellofoodbox is a community initiative promoting 
	the local economy, encouraging sustainable 
	agricultural practices and contributing to a 
	fair, connected and resilient community. 
	Distributing local, seasonal, affordable, healthy, 
	fresh food that is accessible to Bellingen, Urunga, 
	and Coffs Harbour areas. </p>

	<p>Thank you for your generous support and being 
	part of this wonderful project!</p>
	
</body>
</html>
