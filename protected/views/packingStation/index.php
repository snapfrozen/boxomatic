<?php
/* @var $this PackingStationController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Packing Stations',
);

$this->menu=array(
	array('label'=>'Create PackingStation', 'url'=>array('create')),
	array('label'=>'Manage PackingStation', 'url'=>array('admin')),
);
?>

<h1>Packing Stations</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
