<?php
$this->menu=array(
	array('label'=>'List Grower', 'url'=>array('index')),
	array('label'=>'Create Grower', 'url'=>array('create')),
	array('label'=>'Update Grower', 'url'=>array('update', 'id'=>$model->grower_id)),
	array('label'=>'Delete Grower', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->grower_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Grower', 'url'=>array('admin')),
);
?>

<h1><?php echo $model->grower_name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'grower_website',
			'type'=>'raw',
			'value'=>CHtml::link($model->grower_website, Yii::app()->snapFormat->createExternalUrl($model->grower_website)),
		),
		'grower_distance_kms',
	),
)); ?>

<?php if(Yii::app()->user->checkAccess('admin')): ?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'grower_bank_account_name',
		'grower_bank_bsb',
		'grower_bank_acc',
		'grower_certification_status',
		'grower_order_days',
		'grower_produce',
		'grower_notes',
		'grower_payment_details',
	),
)); ?>

<?php endif; ?>