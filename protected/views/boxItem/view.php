<?php
$this->menu=array(
	array('label'=>'List BoxItem', 'url'=>array('index')),
	array('label'=>'Create BoxItem', 'url'=>array('create')),
	array('label'=>'Update BoxItem', 'url'=>array('update', 'id'=>$model->box_item_id)),
	array('label'=>'Delete BoxItem', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->box_item_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage BoxItem', 'url'=>array('admin')),
);
?>

<h1>View BoxItem</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'box_item_id',
		'item_name',
		'box_id',
		'item_value',
		'supplier_id',
	),
)); ?>
