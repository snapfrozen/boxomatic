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

<h1>Manage Inventory</h1>

<?php echo CHtml::link('Add new record',array('create'),array('class'=>'button small')) ?>&nbsp;

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'inventory-grid',
	'dataProvider'=>$model->searchIndex(),
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
		array(
			'name' => 'sum_quantity',
			'filter' => false,
		),
		array(
			'name' => 'sum_box_reserve',
			'filter' => false,
		),
		array(
			'name' => 'total_quantity',
			'filter' => false,
			'type' => 'raw',
			'value' => '$data->total_quantity'
		 ),
		'supplierProduct.wholesale_price',
		'supplierProduct.price',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{Adjust Quantities}',
			'buttons'=>array(
				'Adjust Quantities'=>array(
					'url'=> 'array("inventory/create","product"=>$data->supplier_product_id)',
					'options'=>array('class'=>'text'),
				)
			)
		),
	),
)); ?>
