<?php
/* @var $this CustomerLocationController */
/* @var $model CustomerLocation */

$this->breadcrumbs=array(
	'Customer Locations'=>array('index'),
	$model->customer_location_id,
);

$this->menu=array(
	array('label'=>'List CustomerLocation', 'url'=>array('index')),
	array('label'=>'Create CustomerLocation', 'url'=>array('create')),
	array('label'=>'Update CustomerLocation', 'url'=>array('update', 'id'=>$model->customer_location_id)),
	array('label'=>'Delete CustomerLocation', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->customer_location_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CustomerLocation', 'url'=>array('admin')),
);
?>

<h1>View CustomerLocation #<?php echo $model->customer_location_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'Location.location_name',
		'address',
		'address2',
		'suburb',
		'state',
		'postcode',
		'phone',
	),
)); ?>
