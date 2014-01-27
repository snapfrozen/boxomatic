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
		'delivery_date_formatted',
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
		array(
			'name' => 'supplierPurchase.item_sales_price',
			'filter' => false,
			'type' => 'raw',
			'value' => 'CHtml::link(Yii::app()->snapFormat->currency(CHtml::value($data,"supplierPurchase.item_sales_price")),array("supplierPurchase/update","id"=>$data->supplier_purchase_id,"#"=>"sales"))'
		),
		array(
			'name' => 'supplierPurchase.wholesale_price',
			'filter' => false,
			'type' => 'raw',
			'value' => 'Yii::app()->snapFormat->currency(CHtml::value($data,"supplierPurchase.wholesale_price"))'
		),
		array(
			'class'=>'SnapButtonColumn',
			'template'=>'{Adjust Quantities}',
			'buttons'=>array(
				'Adjust Quantities' => array
				(
					'label' => '<i class="fi fi-page-edit"></i>',
					'options' => array('title'=>'Adjust Quantities'),
					'imageUrl' => false,
				),
			)
		),
	),
)); ?>
