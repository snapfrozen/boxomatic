<?php $appName = Yii::App()->name; ?>
<?php $adminEmail = Yii::App()->params['adminEmail']; ?>
<div class="row">
	<div class="large-8 columns">
		
		<h2>Registration Successful</h2>

		<h4>Hi <?php echo $User->first_name ?>!</h4>	
 
		<p>Thank you for joining the <?php echo $appName ?> project.
		We look forward to supplying you with lots of 
		fresh, local seasonal produce in the future.</p>

		<p>You can check your account status and previous 
		orders <?php echo CHtml::link('here', array('extras/order')) ?>.</p>

		<p>When depositing money via bank transfer, please use your <?php echo $appName ?> ID as your reference</p>

		<div class="panel">
			<p>Your <?php echo $appName ?> ID is: <strong><?php echo $User->bfb_id; ?></strong>.</p>
		</div>

		<p>Each time you make a payment, we will send an email
		confirming receipt of payment into our account.</p>

		<p>Each week you place an order, you will receive an
		email from us confirming your order, date and location
		of pick up, if you have sufficient credit in your <?php echo $appName ?> account.</p>

		<p>You are welcome to top up your account 
		credit by making additional payments at anytime. 
		Box payments are only deducted each week as 
		your order is processed.</p>

		<p>If you have placed an order and we do not receive 
		your payment by Midnight Wednesday, we will send you 
		an email stating that your order has been Declined 
		due to insufficient credit.</p>

		<p>Kind regards,</p>
		<h6>Box-o-Matic, Box Processing System</h6>
	</div>
	<div class="large-4 columns">
		<h2>About Us</h2>

		<p><?php echo $appName ?> is a community initiative promoting the local 
		economy, encouraging sustainable agricultural practices
		and contributing to a fair, connected and resilient community. 
		Distributing local, seasonal, affordable, healthy, fresh food that 
		is accessible to Bellingen, Dorrigo, Urunga, and Coffs Harbour areas.</p>

		<p>Thank you for your generous support and being part of this wonderful project.</p>

		<p>Email: <a href="mailto:<?php echo $adminEmail ?>"><?php echo $adminEmail ?></a></p>
	</div>
</div>


