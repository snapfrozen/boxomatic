<?php
/* @var $this OrderItemController */
/* @var $model OrderItem */

$this->breadcrumbs=array(
	'Customer Delivery Date Items'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List OrderItem', 'url'=>array('index')),
	array('label'=>'Manage OrderItem', 'url'=>array('admin')),
);
?>

<h1>Create OrderItem</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>