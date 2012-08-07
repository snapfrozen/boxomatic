<?php
$this->menu=array(
	array('label'=>'List User', 'url'=>array('index')),
	array('label'=>'Create User', 'url'=>array('create')),
	array('label'=>'Update User', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete User', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage User', 'url'=>array('admin')),
);
?>

<h1><?php echo $model->user_name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'user_email',
			'type'=>'raw',
			'value'=>CHtml::mailto($model->user_email,$model->user_email)
		),	
		'user_name',
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

<h2>Delivery details</h2>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model->Customer,
	'attributes'=>array(
		'Location.location_name',
		'Location.location_delivery_value',
		'customer_notes',
	),
)); ?>


<div class="half">

	<h2>Orders and Payments</h2>

	<div class="info">
		<div class="row">
			<span class="label">Total Orders</span>
			<span class="value number"><?php echo $model->Customer->totalOrders ?></span>
		</div>
		<div class="row">
			<span class="label">Total Payments</span>
			<span class="value number"><?php echo $model->Customer->totalPayments ?></span>
		</div>
		<div class="row total">
			<span class="label">Balance</span>
			<span class="value number"><?php echo $model->Customer->balance ?></span>
		</div>
	</div>

</div>

<?php endif; //Customer ?>

