<?php
$this->breadcrumbs=array(
	'Customer Payments'=>array('index'),
	$model->payment_id,
);

$this->menu=array(
	array('label'=>'List CustomerPayment', 'url'=>array('index')),
	array('label'=>'Create CustomerPayment', 'url'=>array('create')),
	array('label'=>'Update CustomerPayment', 'url'=>array('update', 'id'=>$model->payment_id)),
	array('label'=>'Delete CustomerPayment', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->payment_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CustomerPayment', 'url'=>array('admin')),
);
?>

<h1>View CustomerPayment #<?php echo $model->payment_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'payment_id',
		'payment_value',
		'payment_type',
		'payment_date',
		'customer_id',
	),
)); ?>
