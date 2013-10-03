<?php
	$controller = Yii::app()->controller->id; //current controller
	$action = Yii::app()->controller->getAction()->getId(); //current action
	Yii::app()->clientScript->registerCoreScript('jquery');
?>
<!DOCTYPE html>
	<!--[if IE 8]> 				 <html class="no-js lt-ie9" lang="en" > <![endif]-->
	<!--[if gt IE 8]><!--> <html class="no-js" lang="en" > <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width">
		<title><?php echo isset($this->pageTitle) ? $this->pageTitle : Yii::app()->name; ?></title>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/foundation/foundation.css">
		<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/stepper/jquery.stepper.min.css">
		<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/foundation/custom-foundation.css">
		<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/chosen.css">
		<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/vendor/custom.modernizr.js"></script>
	</head>
	<body>
	<header class="container">
		<!-- <div class="row"> -->
		<nav class="top-bar" data-options="is_hover:true">
			<ul class="title-area">
			   <li class="name">
			     <h1><a href="<?php echo Yii::app()->request->baseUrl; ?>"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/logo-sm.png" title='Bellofoodbox' alt=""></a></h1>
			   </li>
			   <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
			 </ul>
			<section class="top-bar-section">
			 <!-- Left Nav Section -->
			<ul class="right">
				<li><a href="/index.php?r=site/index">Home</a></li>

				<?php if(Yii::app()->user->customer_id): ?>
				<li class="divider"></li>
				<li><a href="/index.php?r=user/view&id=<?php echo Yii::app()->user->id; ?>">Profile</a></li>
				<li class="divider"></li>
				<li><a href="/index.php?r=customerBox/order">Orders</a></li>
				<li class="divider"></li>
				<li><a href="index.php?r=customerPayment">Payments</a></li>
				<?php endif; ?>

				<?php if(Yii::app()->user->checkAccess('admin')): ?>
				<li class="divider"></li>
				<li><a href="/index.php?r=customerPayment/enterPayments">Payments</a></li>
				<li class="divider"></li>
				<li class="has-dropdown"><a href="/index.php?r=user/customers">Customers</a>
					<ul class="dropdown">
						<li><a href="/index.php?r=boxItem/customerBoxes">Customer Orders</a></li>
					</ul>
				</li>
				<li class="divider"></li>
				<li class="has-dropdown"><a href="/index.php?r=grower/admin">Growers</a>
					<ul class="dropdown">
						<li><a href="/index.php?r=growerItem/admin">Inventory</a></li>
						<li><a href="/index.php?r=growerPurchase/admin">Grower Purchases</a></li>
						<li><a href="/index.php?r=grower/map">Grower Map</a></li>
					</ul>
				</li>
				<li class="divider"></li>
				<li><a href="/index.php?r=location/admin">Locations</a></li>
				<li class="divider"></li>
				<li><a href="/index.php?r=user/admin">Admin Users</a></li>
				<li class="divider"></li>
				<li class="has-dropdown"><a href="#">Reports</a>
					<ul class="dropdown">
						<li><a href="/index.php?r=site/creditReport">Credit</a></li>
						<li><a href="/index.php?r=site/salesReport">Box Sales</a></li>
					</ul>
				</li>
				<?php endif; ?>

				<?php if(Yii::app()->user->isGuest): ?>
				<li class="divder"></li>
				<li><a href="/index.php?r=site/register">Register</a></li>
				<?php endif; ?>

				<?php if(!Yii::app()->user->isGuest): ?>
				<li class="has-form mobile-hidden"><a class="button" href="#" data-dropdown="topUserBtn"><?php echo Yii::app()->user->name; ?></a></li>
				<ul id="topUserBtn" class="f-dropdown content" data-dropdown-content>
				  <li><a href="/index.php?r=user/view&id=<?php echo Yii::app()->user->id; ?>">Profile</a></li>
				  <li><a href="/index.php?r=site/logout">Logout</a></li>
				</ul>
			  	<li class='desktop-hidden has-form'><a href="/index.php?r=site/logout" class='button'>Logout</a></li>
				<?php else: ?>
				<li class='has-form'><a href="/index.php?r=site/login" class="button">Login</a></li>
				<?php endif; ?>
			</ul>
			</section>			
		</nav>
		<!-- </div> -->
	</header>
	<div class="container content">
		<?php echo $content; ?>
	</div>
	<footer class="container">
		<div class="row">
			<div class="large-4 columns">
				<p>&copy; Snapfrozen Pty Ltd</p>
			</div>
			<div class="large-4 large-offset-4 columns">
				<div class="right">
					<p>Made with Love by <a href="http://www.snapfrozen.com.au/">Snapfrozen</a></p>
				</div>
			</div>
		</div>
	</footer>

	<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/foundation.min.js"></script>
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/foundation/foundation.forms.js"></script>
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/foundation/foundation.dropdown.js"></script>
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/foundation/foundation.abide.js"></script>
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/vendor/jquery.stepper.min.js"></script>	
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/chosen.jquery.min.js"></script>	
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/site-scripts.js"></script>	

	</body>
</html>
