<?php
$this->menu=array(
	array('label'=>'List Week', 'url'=>array('index')),
	array('label'=>'Create Week', 'url'=>array('create')),
	array('label'=>'Update Week', 'url'=>array('update', 'id'=>$model->week_id)),
	array('label'=>'Delete Week', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->week_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Week', 'url'=>array('admin')),
);
?>

<h1>View Week #<?php echo $model->week_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'week_id',
		'week_num',
		'week_notes',
	),
)); ?>
