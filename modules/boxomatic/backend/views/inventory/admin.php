<?php
$this->breadcrumbs=array(
	'Box-O-Matic'=>array('/snapcms/boxomatic/index'),
	'Inventory'=>array('inventory/index'),
	'Log',
);
$this->menu=array(
//	array('icon' => 'glyphicon glyphicon-plus-sign', 'label'=>'Add a new record', 'url'=>array('create')),
//	array('icon' => 'glyphicon glyphicon-export', 'label'=>'Export Suppliers', 'url'=>array('export')),
);
$this->page_heading = 'Inventory';
?>
<?php
$this->beginWidget('bootstrap.widgets.BsPanel', array(
	'title'=>'&nbsp;',
));
?>
<?php $this->widget('bootstrap.widgets.BsGridView', array(
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
		'notes',
		array(
			'class'=>'bootstrap.widgets.BsButtonColumn',
		),
	),
)); ?>
<?php $this->endWidget(); ?>