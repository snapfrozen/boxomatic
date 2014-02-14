<?php $this->pageTitle=Yii::app()->name; ?>
<div class="row">

	<div class="large-4 columns">
		<h1>Welcome to <?php echo Yii::app()->name ?></h1>
	</div>

	<?php if(Yii::app()->user->isGuest): ?>
	
	<div class="large-4 columns">
		<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/login.png" alt="">
		<h4>Existing Customers</h4>
		<p>If you are already a Food Garden customer, please click the button below to login.</p>
		<?php echo CHtml::link('Login',array('site/login'),array('class'=>'button')); ?>
	</div>

	<div class="large-4 columns">
		<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/customer.png" alt="">
		<h4>New Customers</h4>
		<p>If you're new here and want to make an order, please register by clicking the button below.</p>
		<?php echo CHtml::link('Register',array('site/register'),array('class'=>'button')); ?>
	</div>

	<?php else: ?>

	<!-- <div class="large-8 eight columns"> -->
	<div class="large-8 columns">
		<div class="large-4 columns">
			<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/credits.png" alt="">
			<h4>Add Credits</h4>
			<p>You can add credit to your account at any time.</p>
			<a href="index.php?r=customerPayment/create" class="button">Learn More</a>
		</div>
		<div class="large-4 columns">
			<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/order.png" alt="">
			<h4>Place Orders</h4>
			<p>You can order as far into the future as you like. However, we'll only pack your box if you have enough credit in your account.</p>
			<p>
				It's perfectly fine to place orders into the future, then only put credit into your account on the weeks you'd like a box.
			</p>
			<a href="index.php?r=customerBox/order" class="button">Learn More</a>
		</div>
		<div class="large-4 columns">
			<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/profile.png" alt="">
			<h4>Manage Profile</h4>
			<p>Your profile allows you to manage your contact details and your delivery locations</p>
			<a href="index.php?r=user/update&id=<?php echo Yii::app()->user->id ?>" class="button">Learn More</a>
		</div>
	</div>
	<!-- </div> -->
	<?php endif; ?>
</div>