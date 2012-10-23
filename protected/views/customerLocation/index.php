<?php
/* @var $this CustomerLocationController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Customer Locations',
);

$this->menu=array(
	array('label'=>'Create CustomerLocation', 'url'=>array('create')),
	array('label'=>'Manage CustomerLocation', 'url'=>array('admin')),
);
?>

<h1>Customer Locations</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
