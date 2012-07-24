<?php
$this->breadcrumbs=array(
	'Growers'=>array('index'),
	$model->grower_id,
);

$this->menu=array(
	array('label'=>'List Grower', 'url'=>array('index')),
	array('label'=>'Create Grower', 'url'=>array('create')),
	array('label'=>'Update Grower', 'url'=>array('update', 'id'=>$model->grower_id)),
	array('label'=>'Delete Grower', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->grower_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Grower', 'url'=>array('admin')),
);
?>

<h1>View Grower #<?php echo $model->grower_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'grower_id',
		'grower_name',
		'grower_mobile',
		'grower_phone',
		'grower_address',
		'grower_address2',
		'grower_suburb',
		'grower_state',
		'grower_postcode',
		'grower_distance_kms',
		'grower_bank_account_name',
		'grower_bank_bsb',
		'grower_bank_acc',
		'grower_email',
		'grower_website',
		'grower_certification_status',
		'grower_order_days',
		'grower_produce',
		'grower_notes',
		'grower_payment_details',
	),
)); ?>
