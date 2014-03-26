<?php
/* @var $this InventoryController */
/* @var $model Inventory */
$this->breadcrumbs=array(
	'Box-O-Matic'=>array('/snapcms/boxomatic/index'),
	'Inventory'=>array('inventory/index'),
	'Log'=>array('inventory/admin'),
	'Create a new record',
);
$this->page_heading = 'Create Inventory Record';
$this->menu=array(
//	array('label'=>'List Inventory', 'url'=>array('index')),
//	array('label'=>'Manage Inventory', 'url'=>array('admin')),
);
?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>