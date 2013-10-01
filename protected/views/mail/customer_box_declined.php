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
  <li><strong>Direct Deposit:</strong> BSB: 082469, ACC: 144056759, ACC Name: Bellingen Green Grocers Pty Ltd.<br />
  When depositing money via bank transfer, please use your Bellofoodbox ID as your reference.<br />
  Your Bellofoodbox ID is: <strong><?php echo $Customer->User->bfb_id; ?></strong>.</li>
  <li><strong>Cash:</strong> In store at Bellingen Greengrocers, Cnr Hyde & Church Sts, Bellingen, until the Wednesday before your order.</p></li>
</ul>

<p>
--<br />
Kind regards,<br />
Box-o-matic, Box Processing System</p>


<p>Thank you for your generous support and being part of this wonderful project.</p>

<p>Email: <a href="mailto:info@bellofoodbox.org.au">info@bellofoodbox.org.au</a></p>
</body>
</html>

