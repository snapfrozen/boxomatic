<?php
/* @var $this Controller */
$themeUrl = $baseUrl = Yii::app()->request->baseUrl;
if (Yii::app()->theme)
	$themeUrl = Yii::app()->theme->baseUrl;

$user = Yii::app()->user;
?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="shortcut icon" href="<?php echo $baseUrl ?>/images/favicon.ico">

		<title><?php echo CHtml::encode($this->pageTitle); ?></title>

		<!-- Custom styles for this template -->
		<link href="<?php echo $themeUrl ?>/css/bootstrap.min.css" rel="stylesheet">
		<link href="<?php echo $themeUrl ?>/css/admin.css" rel="stylesheet">
		<link href="<?php echo $themeUrl; ?>/css/lib/chosen.min.css" rel="stylesheet" media="screen" />

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->

		<script type="text/javascript">
			SnapCMS = {};
			SnapCMS.baseUrl = "<?php echo $baseUrl ?>";
		</script>
	</head>
	<body>

		<div class="container">
			<div id="header" class="masthead clearfix">
				<img id="logo" alt="SnapCMS" src="<?php echo $themeUrl ?>/images/snap-logo.gif" />
				<h3 class="text-muted"><?php echo Yii::app()->name ?></h3>
			</div><!-- header -->

			<?php
			$this->widget('bootstrap.widgets.BsNavbar', array(
				'collapse' => true,
				'brandLabel' => false,
				//'brandUrl' => Yii::app()->homeUrl,
				//'position' => BSHtml::NAVBAR_POSITION_STATIC_TOP,
				'items' => array(
					array(
						'class' => 'bootstrap.widgets.BsNav',
						'type' => 'navbar',
						'activateParents' => true,
						'items'=>array(
							array('label'=>'Admin', 'url'=>array('/snapcms/default/index')),
							array('label'=>'View Site', 'url'=>array('/site/index')),
							array('label'=>'Content', 'url'=>array('/snapcms/content/admin')),
							array('label'=>'Menus', 'url'=>array('/snapcms/menu/admin')),
							array(
								'label'=>'Users',
								'items'=>UserController::getMenuArray(),
								'visible' => $user->checkAccess('View User'),
							)
						)
					),
					array(
						'class' => 'bootstrap.widgets.BsNav',
						'type' => 'navbar',
						'htmlOptions' => array(
							'class' => 'navbar-right',
						),
						'activateParents' => true,
						'items'=>array(
							array('label'=>'Logout (' . Yii::app()->user->first_name . ')', 'url'=>array('/site/logout')),
						),
					),
				)
			));
			?>

			<?php if (isset($this->breadcrumbs)): ?>
				<?php
				$this->widget('bootstrap.widgets.BsBreadCrumb', array(
					'links' => $this->breadcrumbs,
				));
				?><!-- breadcrumbs -->
				<?php endif ?>

			<?php foreach (Yii::app()->user->getFlashes() as $key => $message) : ?>
				<div class="alert alert-<?php echo $key ?>">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<?php echo $message ?>
				</div>
			<?php endforeach; ?>

			<?php echo $content; ?>

			<footer class="footer text-muted">
				<p>SnapCMS by <strong><a href="http://snapfrozen.com.au">Snapfrozen</a></strong></p>
			</footer>
		</div>

		<!-- This overrides the yii jquery and breaks things -->
		<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> -->
		<script src="<?php echo $themeUrl; ?>/js/lib/bootstrap.min.js"></script>
		<script src="<?php echo $themeUrl; ?>/js/lib/chosen.jquery.min.js"></script>
		<script src="<?php echo $themeUrl; ?>/js/default.js"></script>

		<?php
		$controller = Yii::app()->controller->id; //set current controller
		$action = Yii::app()->controller->getAction()->getId();
		$jsActionFile = 'js/' . strtolower($controller) . '/' . strtolower($action) . '.js'; // filename to load

		if (is_file($jsActionFile)) : ?>
			<script type="text/javascript" src="<?php echo $themeUrl . '/' . $jsActionFile; ?>"></script>
		<?php endif ?>
		<?php echo CHtml::hiddenField('baseUrl', $themeUrl); ?>
	</body>
</html>