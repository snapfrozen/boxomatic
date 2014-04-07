<?php
$this->breadcrumbs=array(
	'Box-O-Matic'=>array('/snapcms/boxomatic/index'),
	'Inventory'
);
$this->menu=array(
	array('icon' => 'glyphicon glyphicon-list-alt', 'label'=>'View log', 'url'=>array('admin')),
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
		'delivery_date_formatted',
		array(
			'name' => 'sum_quantity',
			'filter' => false,
		),
		array(
			'name' => 'supplierPurchase.item_sales_price',
			'filter' => false,
			'type' => 'raw',
			'value' => 'CHtml::link(SnapFormat::currency(CHtml::value($data,"supplierPurchase.item_sales_price")),array("supplierPurchase/update","id"=>$data->supplier_purchase_id,"#"=>"sales"))'
		),
		array(
			'name' => 'supplierPurchase.wholesale_price',
			'filter' => false,
			'type' => 'raw',
			'value' => 'SnapFormat::currency(CHtml::value($data,"supplierPurchase.wholesale_price"))'
		),
		array(
			'name' => 'supplierProduct.limited_stock',
			'filter' => false,
			'type' => 'raw',
			'value' => 'CHtml::value($data,"supplierProduct.limited_stock") ? "Yes" : "No"'
		),
		array(
			'class'=>'bootstrap.widgets.BsButtonColumn',
			'template'=>'{Adjust Quantities}',
			'buttons'=>array(
				'Adjust Quantities' => array
				(
					'label' => '<i class="glyphicon glyphicon-pencil"></i>',
					'options' => array('title'=>'Adjust Quantities'),
					'url'=> 'array("inventory/create","purchase"=>$data->supplier_purchase_id)',
				),
			)
		),
	),
)); ?>
<?php $this->endWidget(); ?>