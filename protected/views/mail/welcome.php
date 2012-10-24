<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Welcome to Bellofoodbox</title>
</head>
<body style="margin:0;">
	<p>Hi <?php echo $User->first_name ?>,</p>

	<p>Thank you for joining the Bellofoodbox project.
	We look forward to supplying you with lots of 
	fresh, local seasonal produce.</p>

	<p>Your login details are:</p>
	<p>Username:<b><?php echo $User->user_email ?></b><br />
	Password:<b><?php echo $newPassword ?></b></p>
	
	<p>You can order boxes and check your account status at <a href="http://www.bellofoodbox.org.au/order/">www.bellofoodbox.org.au/order</a></p>

<p>When depositing money via bank transfer, please use your Bellofoodbox ID as your reference.<br />
Your Bellofoodbox ID is: <strong><?php echo $Customer->User->bfb_id; ?></strong>.

	<p>Each time you make a payment, we will send an email
	confirming receipt of payment into our account.</p>

	<p>For each you week you have an order placed, you will receive an
	email from us confirming your order, date and location
	of pick up, if you have sufficient credit in your Bellofoodbox account.</p>
	
	<p>You are welcome to top up your account 
	credit by making additional payments at anytime. 
	Box payments are only deducted from your Bellofoodbox account each week, as 
	your order is processed.</p>

	<p>If you have placed an order and we do not receive 
	your payment by Midnight Wednesday, we will send you 
	an email stating that your order has been Declined 
	due to insufficient credit.</p>
	
	<p>You can place as many orders as you like into the future. However, we'll only pack your box if you have enough credit in your account.</p>

	<p>	
--<br />
Kind regards,<br />
Box-o-Matic, Box Processing System</p>

<p>Bellofoodbox is a community initiative promoting the local 
economy, encouraging sustainable agricultural practices
 and contributing to a fair, connected and resilient community. 
Distributing local, seasonal, affordable, healthy, fresh food that 
is accessible to Bellingen, Dorrigo, Urunga, and Coffs Harbour areas.</p>

<p>Thank you for your generous support and being part of this wonderful project.</p>

<p>Email: <a href="mailto:info@bellofoodbox.org.au">info@bellofoodbox.org.au</a></p>	
</body>
</html>
