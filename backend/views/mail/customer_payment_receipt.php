<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Payment Confirmation</title>
</head>
<body style="margin:0;">
<p>Hi <?php echo $Customer->first_name ?>,</p>

<p>Thank you for your Payment to Bellofoodbox.</p>

<h3>Bellofoodbox payment details</h3>

<p>Name: <?php echo $Customer->first_name ?> <?php echo $Customer->last_name ?><br />
Date: <?php echo $UserPayment->payment_date ?><br />
Amount: <?php echo $UserPayment->payment_value ?><br />
Method: <?php echo $UserPayment->payment_type ?></p>

<p><strong>Your current balance is $<?php echo $Customer->balance ?></strong></p>

<p>This is NOT an order confirmation.
Details confirming any box orders will be sent 
in a separate email detailing pick up location and date.</p>

<h3>Managing your Bellofoodbox account</h3>

<p>You can check your account status, add credit and view previous 
orders at: 

<a href="http://app.foodbox.org.au">app.foodbox.org.au/</a></p>

<p>You are welcome to top up your account 
credit by making additional payments at anytime. 
Box payments are only deducted each week as 
your orders are processed.</p>

<p>When depositing money via bank transfer, please use your Bellofoodbox ID as your reference.<br />
Your Bellofoodbox ID is: <strong><?php echo $Customer->bfb_id; ?></strong>.

<p>
--<br />
Kind regards,<br />
Box-o-Matic, Box Processing System</p>

<p>Bellofoodbox is run by Bellingen Greengrocers and encourages sustainable agricultural practices and supports local economy and community, distributing local, seasonal, affordable, healthy, fresh food that is accessible to Bellingen, Dorrigo, Urunga, Valla and Coffs Harbour areas.</p>

<p>Thank you for your generous support and being part of this wonderful project.</p>

<p>Email: <a href="mailto:info@bellofoodbox.org.au">info@bellofoodbox.org.au</a></p>

</body>
</html>
