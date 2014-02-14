<?php
/* @var $this MenuController */
/* @var $model Menu */

$this->breadcrumbs=array(
	'Menus'=>array('index'),
	'Create',
);
?>

<div class="page-header">
	<h1 class="text-muted">Create Menu</h1>
</div>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>