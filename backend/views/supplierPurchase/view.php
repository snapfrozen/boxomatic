<?php
/* @var $this SupplierPurchaseController */
/* @var $model SupplierPurchase */

$this->breadcrumbs=array(
	'Supplier Purchases'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List SupplierPurchase', 'url'=>array('index')),
	array('label'=>'Create SupplierPurchase', 'url'=>array('create')),
	array('label'=>'Update SupplierPurchase', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete SupplierPurchase', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SupplierPurchase', 'url'=>array('admin')),
);
?>
<div class="row">
	<div class="large-12 columns">
		
<h1>View SupplierPurchase #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'id',
		'supplier_product_id',
		'propsed_quantity',
		'propsed_price',
		'proposed_delivery_date',
		'order_notes',
		'delivered_quantity',
		'final_price',
		'delivery_notes',
	),
)); ?>

	</div>
</div>