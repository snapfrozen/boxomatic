<?php
/* @var $this InventoryController */
/* @var $model Inventory */

$this->breadcrumbs=array(
	'Inventories'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Inventory', 'url'=>array('index')),
	array('label'=>'Manage Inventory', 'url'=>array('admin')),
);
?>

<h1>Create Inventory Record</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>