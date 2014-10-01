<?php
/* @var $this Controller */
$cs = Yii::app()->clientScript;
$baseUrl = Yii::app()->baseUrl;
$themeUrl = Yii::app()->theme->baseUrl;
$user = Yii::app()->user;
$cs
    ->registerCoreScript('jquery', CClientScript::POS_END);
//->registerCoreScript('jquery.ui',CClientScript::POS_END)
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <meta name="description" content="<?php echo $this->meta_description ?>" />
    <meta name="keywords" content="<?php echo $this->meta_keywords ?>" />
    <meta name="author" content="<?php echo $this->meta_author ?>">

    <!-- Bootstrap -->
    <link href="<?php echo $themeUrl ?>/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo $themeUrl ?>/css/styles.css" rel="stylesheet" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <div class="container">
        <div class="masthead">
            <div class="row">
                <h3 class="col-xs-6 text-muted"><?php echo CHtml::link(Yii::app()->name, $this->createAbsoluteUrl('/'.$baseUrl)) ?></h3>
                <p class="col-xs-6">
                    <span class="current-user pull-right">
                    <?php if(Yii::app()->user->isGuest): ?>
                        <?php echo CHtml::link('Login',array('site/login')) ?>
                    <?php else: ?>
                        <?php echo CHtml::link(Yii::app()->user->first_name, array('/user/update','id'=>Yii::app()->user->id)) ?>
                        (<?php echo CHtml::link('Logout', array('/site/logout')) ?>)
                    <?php endif; ?>
                    </span>
                </p>
            </div>
            <?php
            $items = [];
            $items []= array(
                'label' => 'Shop',
                'url' => array('shop/index'),
            );
            $items []= array(
                'label' => 'Orders',
                'url' => array('shop/checkout'),
                'visible' => Yii::app()->user->checkAccess('customer'),
            );
            $items []= array(
                'label' => 'Payments',
                'url' => array('user/payments'),
                'visible' => Yii::app()->user->checkAccess('customer'),
            );
            //$items = Menu::model('main_menu', $user->checkAccess('Update Menu'))->getMenuList();
            $this->widget('bootstrap.widgets.BsNavbar', array(
                'collapse' => true,
                'brandLabel' => false,
                'brandUrl' => array('/site/index'),
                //'position' => BsHtml::NAVBAR_POSITION_STATIC_TOP,
                'items' => array(
                    array(
                        'class' => 'bootstrap.widgets.BsNav',
                        'type' => 'navbar',
                        'activateParents' => true,
                        'items' => $items,
                    ),
                )
            ));
            ?>
        </div>

        <?php if (isset($this->breadcrumbs)): ?>
        <?php
        $this->widget('bootstrap.widgets.BsBreadcrumb', array(
            'links' => $this->breadcrumbs,
        ));
        ?><!-- breadcrumbs -->
        <?php endif ?>
        
        <div id="main-alerts">
            <?php foreach(Yii::app()->user->getFlashes() as $key => $message) : ?>
            <div class="alert alert-<?php echo $key ?> alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $message ?>
            </div>
            <?php endforeach; ?>
        </div>

        <?php echo $content; ?>

        <div class="footer">
            <p>&copy; Snapfrozen 2014</p>
        </div>
    </div>

    <script src="<?php echo $themeUrl ?>/js/bootstrap.min.js"></script>
    <script src="<?php echo $themeUrl ?>/js/default.js"></script>
    <?php $this->renderPartial('backend.views.layouts._admin_bar'); ?>
</body>
</html>
