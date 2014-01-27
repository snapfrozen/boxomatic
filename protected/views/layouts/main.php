<?php
	$controller = Yii::app()->controller->id; //current controller
	$action = Yii::app()->controller->getAction()->getId(); //current action
	Yii::app()->clientScript->registerCoreScript('jquery');
?><!DOCTYPE html>
	<!--[if IE 8]> 				 <html class="no-js lt-ie9" lang="en" > <![endif]-->
	<!--[if gt IE 8]><!--> <html class="no-js" lang="en" > <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width">
		<title><?php echo isset($this->pageTitle) ? $this->pageTitle : Yii::app()->name; ?></title>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/foundation/foundation.min.css">
		<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/stepper/jquery.stepper.min.css">
		<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/foundation/custom-foundation.css">
		<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/fonts/foundation-icons.css" />
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
				<?php if(Yii::app()->user->shadow_id): ?>
					<li><?php echo CHtml::link('Log back in as ' . Yii::app()->user->shadow_name, array('user/loginAs','id'=>Yii::app()->user->shadow_id),array('class'=>'shadow')); ?></li>
				<?php endif;?>
				<li><?php echo CHtml::link('Home',array('site/index')) ?></li>
				
				<?php if(Yii::app()->user->checkAccess('customer')): ?>
				<li class="divider"></li>
				<li><?php echo CHtml::link('Profile',array('user/view','id'=>Yii::app()->user->id)) ?></li>
				<li class="divider"></li>
				<li><?php echo CHtml::link('Orders',array('customerBox/order')) ?></li>
				<li class="divider"></li>
				<li><?php echo CHtml::link('Payments',array('customerPayment/index')) ?></li>
				<?php endif; ?>

				<?php if(Yii::app()->user->checkAccess('admin')): ?>
				<li class="divider"></li>
				<li class="has-dropdown">
					<?php echo CHtml::link('Boxes',array('boxItem/create')) ?>
					<ul class="dropdown">
						<li><?php echo CHtml::link('Box Packing',array('boxItem/create')) ?></li>
						<li><?php echo CHtml::link('Box Sizes',array('boxSize/admin')) ?></li>
						<li><?php echo CHtml::link('Payments',array('week/admin')) ?></li>
					</ul>
				</li>
				
				<li class="has-dropdown">
					<?php echo CHtml::link('Customer',array('user/customers')) ?>
					<ul class="dropdown">
						<li><?php echo CHtml::link('Manage Customers',array('user/customers')) ?></li>
						<li><?php echo CHtml::link('Orders',array('boxItem/customerBoxes')) ?></li>
						<li><?php echo CHtml::link('Payments',array('customerPayment/enterPayments')) ?></li>
						<li><?php echo CHtml::link('Locations',array('location/admin')) ?></li>
					</ul>
				</li>
				<li class="divider"></li>
				<li class="has-dropdown">
					<?php echo CHtml::link('Supplier',array('supplier/admin')) ?>
					<ul class="dropdown">
						<li><?php echo CHtml::link('Products',array('supplierProduct/admin')) ?></li>
						<li><?php echo CHtml::link('Purchases',array('supplierPurchase/admin')) ?></li>
						<li><?php echo CHtml::link('Supplier Map',array('supplier/map')) ?></li>
					</ul>
				</li>
				<li class="divider"></li>
				<li class="has-dropdown">
					<?php echo CHtml::link('Inventory',array('inventory/index')) ?>
					<ul class="dropdown">
						<li><?php echo CHtml::link('Inventory',array('inventory/index')) ?></li>
						<li><?php echo CHtml::link('Log',array('inventory/admin')) ?></li>
					</ul>
				</li>
				<li class="divider"></li>
				<li class="has-dropdown">
					<a href="#">Reports</a>
					<ul class="dropdown">
						<li><?php echo CHtml::link('Credit',array('site/creditReport')) ?></li>
						<li><?php echo CHtml::link('Box Sales',array('site/salesReport')) ?></li>
					</ul>
				</li>
				<li class="divider"></li>
				<li><?php echo CHtml::link('Admin Users',array('user/admin')) ?></li>
				<?php endif; ?>

				<?php if(Yii::app()->user->isGuest): ?>
				<li class="divder"></li>
				<li><?php echo CHtml::link('Register',array('site/register')) ?></li>
				<?php endif; ?>

				<?php if(!Yii::app()->user->isGuest): ?>
				<li class="has-form mobile-hidden">
					<a class="button" href="#" data-dropdown="topUserBtn"><?php echo Yii::app()->user->name; ?></a></li>
					<ul id="topUserBtn" class="f-dropdown content" data-dropdown-content>
					  <li><?php echo CHtml::link('Profile',array('user/view','id'=>Yii::app()->user->id)) ?></li>
					  <li><?php echo CHtml::link('Logout',array('site/logout')) ?></li>
					</ul>
				<li class='desktop-hidden has-form'>
					<?php echo CHtml::link('Logout',array('site/logout')) ?>
				</li>
				<?php else: ?>
				<li class='has-form'>
					<?php echo CHtml::link('Login',array('site/login')) ?>
				</li>
				<?php endif; ?>
			</ul>
			</section>			
		</nav>
		<!-- </div> -->
	</header>
	<div class="container content">
		<div class="row">
			<div class="large-12 columns">
			<?php echo $content; ?>
			</div>
		</div>
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
	
	<?php
	$jsFile = 'js/' . strtolower($controller) . '/' . strtolower($action) . '.js'; // filename to load
	if( is_file($jsFile) ) { 
		Yii::app()->clientScript->registerCoreScript('jquery');
		Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/' . $jsFile,CClientScript::POS_END);
	} ?>

	</body>
</html>