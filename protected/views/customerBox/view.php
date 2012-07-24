<?php
$this->breadcrumbs=array(
	'Customer Boxes'=>array('index'),
	$model->customer_box_id,
);

$this->menu=array(
	array('label'=>'List CustomerBox', 'url'=>array('index')),
	array('label'=>'Create CustomerBox', 'url'=>array('create')),
	array('label'=>'Update CustomerBox', 'url'=>array('update', 'id'=>$model->customer_box_id)),
	array('label'=>'Delete CustomerBox', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->customer_box_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CustomerBox', 'url'=>array('admin')),
);
?>

<h1>View CustomerBox #<?php echo $model->customer_box_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'customer_box_id',
		'customer_id',
		'box_id',
		'quantity',
	),
)); ?>
