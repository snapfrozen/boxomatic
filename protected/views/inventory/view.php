<?php
/* @var $this InventoryController */
/* @var $model Inventory */

$this->breadcrumbs=array(
	'Inventories'=>array('index'),
	$model->inventory_id,
);

$this->menu=array(
	array('label'=>'List Inventory', 'url'=>array('index')),
	array('label'=>'Create Inventory', 'url'=>array('create')),
	array('label'=>'Update Inventory', 'url'=>array('update', 'id'=>$model->inventory_id)),
	array('label'=>'Delete Inventory', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->inventory_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Inventory', 'url'=>array('admin')),
);
?>

<h1>View Inventory #<?php echo $model->inventory_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'inventory_id',
		'grower_purchase_id',
		'quantity',
		'price',
	),
)); ?>