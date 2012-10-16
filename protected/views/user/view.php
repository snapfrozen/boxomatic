<?php
$this->menu=array(
	array('label'=>'List User', 'url'=>array('index')),
	array('label'=>'Create User', 'url'=>array('create')),
	array('label'=>'Update User', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete User', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage User', 'url'=>array('admin')),
);
?>

<h1><?php echo $model->full_name; ?></h1>

<p><?php echo CHtml::link('Update',array('user/update','id'=>$model->id)) ?></p>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
			'name'=>'user_email',
			'type'=>'raw',
			'value'=>CHtml::mailto($model->user_email,$model->user_email)
		),	
		'full_name',
		'user_phone',
		'user_mobile',
		'user_address',
		'user_address2',
		'user_suburb',
		'user_state',
		'user_postcode',
		'last_login_time',
	),
)); ?>

<?php if($model->Customer): ?>

<h2>Default delivery location</h2>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model->Customer,
	'attributes'=>array(
		'Location.location_name',
		'Location.location_delivery_value',
		array(
			'name'=>'CustomerLocation.address',
			'visible'=>isset($model->Customer->CustomerLocation),
		),
		'customer_notes',
	),
)); ?>

<?php endif; //Customer ?>

