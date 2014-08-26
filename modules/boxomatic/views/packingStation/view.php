<?php
/* @var $this PackingStationController */
/* @var $model PackingStation */

$this->breadcrumbs=array(
	'Packing Stations'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List PackingStation', 'url'=>array('index')),
	array('label'=>'Create PackingStation', 'url'=>array('create')),
	array('label'=>'Update PackingStation', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete PackingStation', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage PackingStation', 'url'=>array('admin')),
);
?>

<h1>View PackingStation #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
	),
)); ?>
