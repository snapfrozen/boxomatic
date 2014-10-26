<?php
/* @var $this PackingStationController */
/* @var $model PackingStation */

$this->breadcrumbs=array(
	'Box-O-Matic'=>array('/snapcms/boxomatic/index'),
	'Packing Stations',
);
$this->menu=array(
	array('icon' => 'glyphicon glyphicon-plus-sign', 'label'=>'Create Packing Station', 'url'=>array('packingStation/create')),
);
$this->page_heading = 'Packing Stations';
?>
<?php
$this->beginWidget('bootstrap.widgets.BsPanel', array(
	'title'=>'&nbsp;',
));
?>
<?php $this->widget('bootstrap.widgets.BsGridView', array(
	'id'=>'packing-station-grid',
	'dataProvider'=>$model->search(),
	'cssFile' => '', 
	'filter'=>$model,
	'columns'=>array(
		'name',
		array(
			'class'=>'bootstrap.widgets.BsButtonColumn',
			'template'=>'{update}{delete}'
		),
	),
)); ?>
<?php $this->endWidget(); ?>