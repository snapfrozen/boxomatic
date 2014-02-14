<?php
/* @var $this PackingStationController */
/* @var $model PackingStation */

$this->breadcrumbs=array(
	'Packing Stations'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List PackingStation', 'url'=>array('index')),
	array('label'=>'Create PackingStation', 'url'=>array('create')),
	array('label'=>'View PackingStation', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage PackingStation', 'url'=>array('admin')),
);
?>

<h1>Update PackingStation <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>