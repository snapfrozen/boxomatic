<?php
$this->breadcrumbs=array(
	'Box Sizes'=>array('index'),
	$model->box_sizes,
);

$this->menu=array(
	array('label'=>'List BoxSize', 'url'=>array('index')),
	array('label'=>'Create BoxSize', 'url'=>array('create')),
	array('label'=>'Update BoxSize', 'url'=>array('update', 'id'=>$model->box_sizes)),
	array('label'=>'Delete BoxSize', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->box_sizes),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage BoxSize', 'url'=>array('admin')),
);
?>

<h1>View BoxSize #<?php echo $model->box_sizes; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'box_sizes',
		'box_size_name',
		'box_size_value',
		'box_size_markup',
		'box_size_price',
	),
)); ?>
