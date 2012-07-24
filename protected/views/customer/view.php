<?php
$this->breadcrumbs=array(
	'Customers'=>array('index'),
	$model->customer_id,
);

$this->menu=array(
	array('label'=>'List Customer', 'url'=>array('index')),
	array('label'=>'Create Customer', 'url'=>array('create')),
	array('label'=>'Update Customer', 'url'=>array('update', 'id'=>$model->customer_id)),
	array('label'=>'Delete Customer', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->customer_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Customer', 'url'=>array('admin')),
);
?>

<h1>View Customer #<?php echo $model->customer_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'customer_id',
		'customer_name',
		'customer_phone',
		'customer_mobile',
		'customer_address',
		'customer_address2',
		'customer_suburb',
		'customer_state',
		'customer_postcode',
		'location_id',
		'customer_notes',
		'customer_email',
		'customer_password',
	),
)); ?>
