<?php
/* @var $this ContentController */
/* @var $model Content */

$this->pageTitle=Yii::app()->name . ' - ' . $Content->title;
$items = Menu::model('main_menu')->getMenuList($MenuItem,2);
$this->menu = $items;
?>
<div class="page-header">
	<h1><?php echo $Content->title ?></h1>
</div>
<div class="row">
	<div class="col-md-12">
		<h2>Latest News</h2>
		<div class="row news">
			<?php foreach($News as $News): ?>
			<div class="col-md-3">
				<h3><?php echo CHtml::link($News->title,array('content/view','id'=>$News->id)) ?></h3>
				<?php echo SnapHtml::activeImage($News, 'image', 'small', $News->title) ?>
				<?php echo SnapHtml::editableArea($News, 'intro', $this->isEditable(),'plain') ?>
			</div>
			<?php endforeach; ?>
		</div>
		<p><?php echo CHtml::link('View All News',array('content/view','path'=>'/news')) ?></p>
		<hr />
	</div>
	<div class="col-md-6">
		<?php echo SnapHtml::editableArea($Content, 'content', $this->isEditable()) ?>
	</div>
	<div class="col-md-6">
		<?php echo SnapHtml::editableArea($Content, 'content_2', $this->isEditable()) ?>
	</div>
</div>