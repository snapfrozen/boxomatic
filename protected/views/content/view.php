<?php
/* @var $this ContentController */
/* @var $model Content */

$this->pageTitle=Yii::app()->name . ' - ' . $Content->title;

$this->breadcrumbs=array(
//	'Contents'=>array('index'),
//	$Content->title,
);

$items = Menu::model('main_menu')->menuList; 
$this->menu = $items;
?>

<div class="page-header">
	<h1 class="text-muted"><?php echo $Content->title ?></h1>
</div>
<div contenteditable="true" id="field_content" data-id="<?php echo $Content->id ?>"> 
	<?php echo $Content->content; ?>
</div>
