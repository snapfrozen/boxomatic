<?php
/* @var $this InventoryController */
/* @var $model Inventory */

$this->breadcrumbs=array(
	'Inventories'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Inventory', 'url'=>array('index')),
	array('label'=>'Create Inventory', 'url'=>array('create')),
);
?>

<h1>Inventory Log</h1>

<?php echo CHtml::link('Add new record',array('create'),array('class'=>'button small')) ?>&nbsp;

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'inventory-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'cssFile' => '', 
	'columns'=>array(
		//'inventory_id',
		array(
			'name' => 'product_name_search',
			'type' => 'raw',
			'value' => 'CHtml::link(CHtml::value($data,"supplierProduct.name"),array("supplierProduct/update","id"=>$data->supplier_product_id))'
		),
		array(
			'name'=>'supplier_name_search',
			'value'=>'CHtml::value($data,"supplierProduct.Supplier.name")',
		),
		//'grower_purchase_id',
		'quantity',
		'box_reserve',
		'notes',
		array(
			'class'=>'SnapButtonColumn',
		),
	),
)); ?>
