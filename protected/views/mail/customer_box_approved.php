<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Your order has been approved.</title>
</head>
<body style="margin:0;">
<p>Hi <?php echo $Customer->User->first_name ?>,</p>

<p><strong>Your Bellofoodbox order for <?php echo $CustomerBox->Box->Week->week_delivery_date ?>, has been APPROVED. 
And our growers are now busy harvesting all the fresh 
yummy produce to fill your Bellofoodbox.</strong></p>

<p>Your pickup/delivery location is <?php echo $CustomerBox->delivery_location ?>.</p>

<p>Your Bellofoodbox account has been debited <?php echo Yii::app()->snapFormat->currency($CustomerBox->Box->box_price+$CustomerBox->delivery_cost) ?></p>

<p>Your current balance is <?php echo Yii::app()->snapFormat->currency($Customer->balance-$CustomerBox->Box->box_price+$CustomerBox->delivery_cost) ?></p>

<h3>Managing your Bellofoodbox account</h3>

<p>You can check your account status, add credit and view previous 
orders at: <a href="http://www.bellofoodbox.org.au/">www.Bellofoodbox.org.au</a></p>

<p>You are welcome to top up your account 
credit by making additional payments at anytime. 
Box payments are only deducted each week as 
your orders are processed.</p>

<p>When depositing money via bank transfer, please use your Bellofoodbox ID as your reference.<br />
Your Bellofoodbox ID is: <strong><?php echo $Customer->User->bfb_id; ?></strong>.

<h3>Information for collections</h3>
 
<p>If you have opted to pick up your box from our headquarters –
 PLEASE NOTE: Any box that is not collected before 6.30pm 
on Wednesday WILL BE DONATED TO CHARITY (no exceptions).</p>

<p>If you are unable to pick up your box, please call us to arrange 
delivery via our Courier ($7 Charge) or why not organise a group 
of friends to car pool pick up :)</p>

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
