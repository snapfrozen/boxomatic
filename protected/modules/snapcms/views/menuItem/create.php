<?php
/* @var $this MenuItemController */
/* @var $model MenuItem */

$this->breadcrumbs=array(
	'Menu Items'=>array('index'),
	'Create',
);
?>

<div class="page-header">
	<h1 class="text-muted">Create MenuItem</h1>
</div>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>