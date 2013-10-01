<div class="row">
<?php $this->pageTitle=Yii::app()->name; ?>

<!-- <p><a href="http://www.bellofoodbox.org.au">Take me back to the Bellofoodbox website</a></p>

<h1>Welcome to Bellofoodbox</h1>

<?php if(Yii::app()->user->isGuest): ?>

<h2>Existing Customers</h2>

<p><a href="index.php?r=site/login">Click here to login</a></p>

<h2>New Customers</h2>

<p><a href="index.php?r=site/register">Click here to sign up</a></p>

<?php else: ?>


<h2>Place Orders</h2>

<p>You can order as far into the future as you like. However, we'll only pack your box if you have enough credit in your account.</p>

<p>It's perfectly fine to place orders into the future, then only put credit into your account on the weeks you'd like a box.</p>

<p><a href="index.php?r=customerBox/order">Click here to manage your orders</a></p>

<h2>Add Credit</h2>

<p>You can add credit to your account at any time.</p>

<p><a href="index.php?r=customerPayment/create">Click here to add credit</a></p>

<h2>Manage Your Profile</h2>

Your profile allows you to manage your contact details and your delivery locations</a></p>

<p><a href="index.php?r=user/update&id=<?php echo Yii::app()->user->id ?>">Click here to manage your profile</a></p>
	
<?php endif; ?> -->

	<div class="large-4 columns">
		<h1>Welcome to Bellofoodbox</h1>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maiores, necessitatibus dolore qui enim nesciunt dicta voluptatem perspiciatis fugit dolores architecto quis vel dolorum hic laborum a provident similique quasi minus?</p>
		<a href="http://www.bellofoodbox.org.au" class="button secondary">Return to Website</a>
	</div>

	<?php if(Yii::app()->user->isGuest): ?>
	
	<div class="large-4 columns">
		<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/login.png" alt="">
		<h4>Existing Customers</h4>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptas, fugit, ea, id, totam labore doloribus qui maiores fuga iure deleniti unde omnis doloremque libero iusto quia error perferendis modi delectus.</p>
		<a href="index.php?r=site/login" class="button">Login</a>
	</div>

	<div class="large-4 columns">
		<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/customer.png" alt="">
		<h4>New Customers</h4>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptas, fugit, ea, id, totam labore doloribus qui maiores fuga iure deleniti unde omnis doloremque libero iusto quia error perferendis modi delectus.</p>
		<a href="index.php?r=site/register" class="button">Register</a>
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