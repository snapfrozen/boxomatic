<?php
$this->breadcrumbs=array(
	'Grower Items'=>array('index'),
	$model->item_name,
);

$this->menu=array(
	array('label'=>'List GrowerItem', 'url'=>array('index')),
	array('label'=>'Create GrowerItem', 'url'=>array('create')),
	array('label'=>'Update GrowerItem', 'url'=>array('update', 'id'=>$model->item_id)),
	array('label'=>'Delete GrowerItem', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->item_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GrowerItem', 'url'=>array('admin')),
);
?>

<h1>View GrowerItem #<?php echo $model->item_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'item_id',
		'grower_id',
		'item_name',
		'item_value',
		'item_unit',
		'item_available_from',
		'item_available_to',
	),
)); ?>
