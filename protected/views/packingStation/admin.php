<?php
/* @var $this PackingStationController */
/* @var $model PackingStation */

$this->breadcrumbs=array(
	'Packing Stations'=>array('index'),
	'Manage',
);

$this->menu=array(
//	array('label'=>'Create Packing Station', 'url'=>array('create')),
);
?>

<h1>Manage Packing Stations</h1>
<div class="panel">
	<?php echo CHtml::link('Create Packing Station',array('packingStation/create'),array('class'=>'button small')) ?>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'packing-station-grid',
	'dataProvider'=>$model->search(),
	'cssFile' => '', 
	'filter'=>$model,
	'columns'=>array(
		'name',
		array(
			'class'=>'application.components.snap.SnapButtonColumn',
		),
	),
)); ?>
