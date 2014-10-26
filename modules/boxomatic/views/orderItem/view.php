<?php
/* @var $this OrderItemController */
/* @var $model OrderItem */

$this->breadcrumbs=array(
	'Customer Delivery Date Items'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List OrderItem', 'url'=>array('index')),
	array('label'=>'Create OrderItem', 'url'=>array('create')),
	array('label'=>'Update OrderItem', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete OrderItem', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage OrderItem', 'url'=>array('admin')),
);
?>

<h1>View OrderItem #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'order_id',
		'supplier_product_id',
		'quantity',
		'price',
		'created',
		'updated',
	),
)); ?>
