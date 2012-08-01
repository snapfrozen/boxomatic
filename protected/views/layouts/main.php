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
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->
	<div id="mainmenu">
		<?php 

		    $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Home', 'url'=>array('/site/index')),
//				array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
//				array('label'=>'Contact', 'url'=>array('/site/contact')),
				
				array('label'=>'Profile', 'url'=>array('/user/view','id'=>Yii::app()->user->id), 'visible'=>!Yii::app()->user->isGuest),
				
				array('label' => 'Customer', 'url' => '#', 'visible' => Yii::app()->user->checkAccess('customer'), 'items' => array(
					array('label'=>'Manage orders', 'url'=>array('customerBox/admin')),
				)),
				
				array('label' => 'Grower', 'url' => '#', 'visible' => Yii::app()->user->checkAccess('grower'), 'items' => array(
					
					array('label'=>'Items', 'url'=>array('growerItem/admin')),
					
				)),
				
				array('label' => 'Admin', 'url' => '#', 'visible' => Yii::app()->user->checkAccess('admin'), 'items' => array(
					array('label'=>'Create a box', 'url'=>array('boxItem/create')),
					array('label'=>'Users', 'url'=>array('user/admin')),
					array('label'=>'Growers', 'url'=>array('grower/admin')),
					array('label'=>'Locations', 'url'=>array('location/admin')),
					array('label'=>'Boxes', 'url'=>array('box/admin')),
					array('label'=>'Boxes Items', 'url'=>array('boxItem/admin')),
					array('label'=>'Box Sizes', 'url'=>array('boxSize/admin'), 'visible'=>Yii::app()->user->checkAccess('admin')),
				)),

				array('label'=>'Register', 'url'=>array('/customer/register'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
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

</body>
</html>
