<?php
/* @var $this CustomerDeliveryDateItemController */
/* @var $model CustomerDeliveryDateItem */

$this->breadcrumbs=array(
	'Customer Delivery Date Items'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List CustomerDeliveryDateItem', 'url'=>array('index')),
	array('label'=>'Create CustomerDeliveryDateItem', 'url'=>array('create')),
	array('label'=>'Update CustomerDeliveryDateItem', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete CustomerDeliveryDateItem', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CustomerDeliveryDateItem', 'url'=>array('admin')),
);
?>

<h1>View CustomerDeliveryDateItem #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'customer_delivery_date_id',
		'supplier_product_id',
		'quantity',
		'price',
		'created',
		'updated',
	),
)); ?>
