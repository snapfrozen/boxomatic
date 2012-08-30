<?php
$this->menu=array(
	array('label'=>'List CustomerPayment', 'url'=>array('index')),
	array('label'=>'Create CustomerPayment', 'url'=>array('create')),
	array('label'=>'Update CustomerPayment', 'url'=>array('update', 'id'=>$model->payment_id)),
	array('label'=>'Delete CustomerPayment', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->payment_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CustomerPayment', 'url'=>array('admin')),
);
?>

<h1>Payment made <?php echo $model->payment_date; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'Customer.User.user_name',
		'payment_type',
		'payment_value',
		'payment_date',
	),
)); ?>
