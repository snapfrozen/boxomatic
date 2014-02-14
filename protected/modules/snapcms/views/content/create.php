<?php
/* @var $this ContentController */
/* @var $model Content */

$this->breadcrumbs=array(
	'Contents'=>array('index'),
	'Create',
);
?>

<div class="page-header">
	<h1 class="text-muted">Create Page - <?php echo $model->ContentType->name ?></h1>
</div>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>