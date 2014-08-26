<?php
/* @var $this InventoryController */
/* @var $model Inventory */

$this->breadcrumbs=array(
	'Inventories'=>array('index'),
	$model->inventory_id=>array('view','id'=>$model->inventory_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Inventory', 'url'=>array('index')),
	array('label'=>'Create Inventory', 'url'=>array('create')),
	array('label'=>'View Inventory', 'url'=>array('view', 'id'=>$model->inventory_id)),
	array('label'=>'Manage Inventory', 'url'=>array('admin')),
);
?>

<h1>Update Inventory <?php echo $model->inventory_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>