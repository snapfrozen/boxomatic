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
	<p>Username: <b><?php echo $User->email ?></b><br />
	Password: <b><?php echo $newPassword ?></b></p>
	
	<p>You can order boxes and check your account status at <a href="http://app.foodbox.org.au/">app.foodbox.org.au</a></p>

<p>When depositing money via bank transfer, please use your Bellofoodbox ID as your bank reference.<br />
Your Bellofoodbox ID is: <strong><?php echo $User->bfb_id; ?></strong>.

	<p>Each time you make a payment, we will send an email
	confirming receipt of payment.</p>
	
	<p>For each week you have an order placed, you'll receive a confirmation email, if there is enough credit in your Bellofoodbox account.</p>
		
	<p>You can top up your Bellofoodbox account at anytime within the Bellofoodbox system using PayPal, or by direct bank transfer.</p> 
	<p>Box payments are only deducted from your Bellofoodbox account each week, as 
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

<p>Bellofoodbox is run by Bellingen Greengrocers and encourages sustainable agricultural practices and supports local economy and community, distributing local, seasonal, affordable, healthy, fresh food that is accessible to Bellingen, Dorrigo, Urunga, Valla and Coffs Harbour areas.</p>

<p>Thank you for your generous support and being part of this wonderful project.</p>

<p>Email: <a href="mailto:info@bellofoodbox.org.au">info@bellofoodbox.org.au</a></p>	
</body>
</html>
