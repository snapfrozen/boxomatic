<?php
$this->menu=array(
	array('label'=>'List BoxSize', 'url'=>array('index')),
	array('label'=>'Create BoxSize', 'url'=>array('create')),
	array('label'=>'Manage BoxSize', 'url'=>array('admin')),
);
?>

<h1>View BoxSize</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'box_size_name',
		'box_size_value',
		'box_size_markup',
		'box_size_price',
	),
)); ?>
