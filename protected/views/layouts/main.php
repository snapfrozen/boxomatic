<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<?php if(isset(Yii::app()->user->shadow_id)):
			echo CHtml::link('Log back in as ' . Yii::app()->user->shadow_name, array('user/loginAs','id'=>Yii::app()->user->shadow_id),array('class'=>'shadow'));
		endif;?>
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->
	<div id="mainmenu">
		<?php 

		    $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Home', 'url'=>array('/site/index')),
//				array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
//				array('label'=>'Contact', 'url'=>array('/site/contact')),
				
				//array('label'=>'Profile', 'url'=>array('/user/view','id'=>Yii::app()->user->id), 'visible'=>!Yii::app()->user->isGuest),
				

				//Customer menu
				array('label'=>'Orders', 'url'=>array('customerBox/order'), 'visible' => Yii::app()->user->customer_id),
//				array('label'=>'Make a payment', 'url'=>array('customerPayment/create'), 'visible' => Yii::app()->user->customer_id),
				array('label'=>'Payments', 'url'=>array('customerPayment/index'), 'visible' => Yii::app()->user->customer_id),

				//Grower menu
				array('label'=>'Items', 'url'=>array('growerItem/admin'), 'visible' => Yii::app()->user->grower_id),
				
				
				//Admin menu
				array('label'=>'Boxes', 'url'=>array('boxItem/create'), 'visible' => Yii::app()->user->checkAccess('admin')),
				array('label'=>'Payments', 'url'=>array('customerPayment/enterPayments'), 'visible' => Yii::app()->user->checkAccess('admin')),
				array('label'=>'Customers', 'url'=>array('user/admin'), 'visible' => Yii::app()->user->checkAccess('admin')),
				array('label'=>'Growers', 'url'=>array('grower/admin'), 'visible' => Yii::app()->user->checkAccess('admin')),
				array('label'=>'Locations', 'url'=>array('location/admin'), 'visible' => Yii::app()->user->checkAccess('admin')),

				array('label'=>'Register', 'url'=>array('site/register'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Login', 'url'=>array('site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			),
		));
		
		?>
	</div><!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> Snapfrozen Pty Ltd.<br/>
		All Rights Reserved.<br/>
	</div><!-- footer -->

</div><!-- page -->
<?php
	$controller = Yii::app()->controller->id; //current controller
	$action = Yii::app()->controller->getAction()->getId(); //current action

	$jsFile = 'js/' . strtolower($controller) . '/' . strtolower($action) . '.js'; // filename to load
	if( is_file($jsFile) ) { 
		Yii::app()->clientScript->registerCoreScript('jquery');
		Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/' . $jsFile,CClientScript::POS_END);
	} ?>
</body>
</html>
