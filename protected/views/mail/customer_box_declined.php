<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Oh No! Your order has been declined.</title>
</head>
<body style="margin:0;">
<p>Hi <?php echo $Customer->User->first_name ?>,</p>

<p>Unfortunately your Bellofoodbox order for 
pick up on <?php $CustomerBox->Box->Week->week_delivery_date ?> has been DECLINED, as you 
have insufficient credit.</p>

<p>Please contact us immediately on if you feel this is an error.</p>

<h3>Topping up your account</h3>

<p>Please top up your Bellofoodbox account to 
ensure you receive a delicious Bellofoodbox, 
full of beautiful fresh, local produce <strong>next week</strong>.</p>

<p>You can top up your balance directly using PayPal at:
<a href="http://www.bellofoodbox.org.au/">www.Bellofoodbox.org.au</a></p>

<p>We also accept Direct Deposits and Cash.</p>
 
<p>When depositing money via bank transfer, please use your Bellofoodbox ID as your reference.<br />
Your Bellofoodbox ID is: <strong><?php echo $Customer->User->bfb_id; ?></strong>.
 
<p>(Payments by Cash are available anytime 
at local Shop Front Order Boxes or in person 
on Wednesday's Only during box pick up between 
3.30pm - 6.30pm).</p>

<p>
--<br />
Kind regards,<br />
Box-o-matic Box Processing System</p>

<p>Bellofoodbox is a community initiative promoting the local 
economy, encouraging sustainable agricultural practices
 and contributing to a fair, connected and resilient community. 
Distributing local, seasonal, affordable, healthy, fresh food that 
is accessible to Bellingen, Dorrigo, Urunga, and Coffs Harbour areas.</p>

<p>Thank you for your generous support and being part of this wonderful project.</p>

<p>Email: <a href="mailto:info@bellofoodbox.org.au">info@bellofoodbox.org.au</a><br />
Phone: 0400 146 085</p>
</body>
</html>

