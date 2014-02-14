<?php
/* @var $this ContentController */
/* @var $model Content */

$this->breadcrumbs=array(
	'Contents'=>array('index'),
	$model->title,
);

$this->operations=array(
	array('label'=>'Create Page', 'url'=>array('/snapcms/contentType/index')),
	array('label'=>'Update Content', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Content', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<div class="page-header">
	<h1 class="text-muted">View Content</h1>
</div>

<?php $this->widget('SnapDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'type',
		'created',
		'updated',
	),
)); ?>
