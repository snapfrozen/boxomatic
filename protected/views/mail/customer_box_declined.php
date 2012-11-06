<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Oh No! Your order has been declined.</title>
</head>
<body style="margin:0;">
<p>Hi <?php echo $Customer->User->first_name ?>,</p>

<p>Unfortunately your Bellofoodbox order for 
pick up on <?php echo $CustomerBox->Box->Week->week_delivery_date ?> has been DECLINED, as you 
have insufficient Bellofoodbox credit.</p>

<p>Please contact us immediately if you feel this is an error.</p>

<h3>Topping up your account</h3>

<p>Please top up your Bellofoodbox account to 
ensure you receive a delicious Bellofoodbox, 
full of beautiful fresh, local produce <strong>next week</strong>.</p>

<ul>
  <li><strong>Online:</strong> You can top up your balance directly using PayPal at <a href="http://app.foodbox.org.au/">app.foodbox.org.au</a></li>
  <li><strong>Direct Deposit:</strong> BSB 704328, Acc 221552 (for BCU customers 221552 S20)<br />
  When depositing money via bank transfer, please use your Bellofoodbox ID as your reference.<br />
  Your Bellofoodbox ID is: <strong><?php echo $Customer->User->bfb_id; ?></strong>.</li>
  <li><strong>Cash:</strong> At the Kombu order box anytime, or in person on Wednesdays only during box pick up between 3.30pm - 6.30pm).</p>
</ul>

<p><strong>BCU Customers:</strong> If you are making a transfer from your BCU account to ours you will need to enter your ID number in the 'Your Reference' field, above the 'Amount' field.  This reference will be displayed on your statement AND our statement.</p>

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

