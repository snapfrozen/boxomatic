<?php
$this->breadcrumbs=array(
	'Box-O-Matic'=>array('/snapcms/boxomatic/index'),
	'Customers'=>array('user/customers'),
	'Locations',
);
$this->page_heading = 'Locations';
$this->menu=array(
	array('icon' => 'glyphicon glyphicon-plus-sign', 'label'=>'Create Location', 'url'=>array('create')),
);
?>
<?php
$this->beginWidget('bootstrap.widgets.BsPanel', array(
	'title'=>'&nbsp;',
));
?>
<?php $this->widget('backend.widgets.SnapGridView', array(
	'id'=>'location-grid',
	'cssFile' => '',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'location_name',
		'location_delivery_value:currency',
		array(
			'name'=>'is_pickup',
			'type'=>'boolean',
			'filter'=>array(1=>'Yes',0=>'No')
		),
		array(
			'class'=>'bootstrap.widgets.BsButtonColumn',
		),
	),
)); ?>
<?php $this->endWidget(); ?>



