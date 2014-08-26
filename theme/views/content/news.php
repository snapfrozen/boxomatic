<?php
/* @var $this ContentController */
/* @var $model Content */

$this->pageTitle=Yii::app()->name . ' - ' . $Content->title;
$this->breadcrumbs = array(
	'News'=>array('content/view','path'=>'news'),
	$Content->title,
);
$items = Menu::model('main_menu')->getMenuList($MenuItem,2);
$this->menu = $items;
if(empty($this->menu)) {
	$this->layout='//layouts/column1';
}

$UserCreated = $Content->UserCreated;
?>

<div class="page-header">
	<h1 class="text-muted"><?php echo $Content->title ?></h1>
</div>
<?php echo SnapHtml::activeImage($Content, 'image', 'small', $Content->title) ?>
<?php echo SnapHtml::editableArea($Content, 'content', $this->isEditable()) ?>

<div class="well pull-left">
	<?php echo SnapHtml::activeImage($UserCreated, 'image', 'medium', $UserCreated->full_name) ?>
	<h4>By <?php echo $UserCreated->full_name ?></h4>
	<?php echo $UserCreated->bio ?>
</div>