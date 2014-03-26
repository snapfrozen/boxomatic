<?php
/* @var $this OrderItemController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Customer Delivery Date Items',
);

$this->menu=array(
	array('label'=>'Create OrderItem', 'url'=>array('create')),
	array('label'=>'Manage OrderItem', 'url'=>array('admin')),
);
?>

<h1>Customer Delivery Date Items</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
