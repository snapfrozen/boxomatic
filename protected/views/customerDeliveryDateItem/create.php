<?php
/* @var $this CustomerDeliveryDateItemController */
/* @var $model CustomerDeliveryDateItem */

$this->breadcrumbs=array(
	'Customer Delivery Date Items'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List CustomerDeliveryDateItem', 'url'=>array('index')),
	array('label'=>'Manage CustomerDeliveryDateItem', 'url'=>array('admin')),
);
?>

<h1>Create CustomerDeliveryDateItem</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>