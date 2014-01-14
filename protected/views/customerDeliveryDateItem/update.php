<?php
/* @var $this CustomerDeliveryDateItemController */
/* @var $model CustomerDeliveryDateItem */

$this->breadcrumbs=array(
	'Customer Delivery Date Items'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List CustomerDeliveryDateItem', 'url'=>array('index')),
	array('label'=>'Create CustomerDeliveryDateItem', 'url'=>array('create')),
	array('label'=>'View CustomerDeliveryDateItem', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CustomerDeliveryDateItem', 'url'=>array('admin')),
);
?>

<h1>Update CustomerDeliveryDateItem <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>