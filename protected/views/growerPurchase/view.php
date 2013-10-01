<?php
/* @var $this GrowerPurchaseController */
/* @var $model GrowerPurchase */

$this->breadcrumbs=array(
	'Grower Purchases'=>array('index'),
	$model->grower_purchases_id,
);

$this->menu=array(
	array('label'=>'List GrowerPurchase', 'url'=>array('index')),
	array('label'=>'Create GrowerPurchase', 'url'=>array('create')),
	array('label'=>'Update GrowerPurchase', 'url'=>array('update', 'id'=>$model->grower_purchases_id)),
	array('label'=>'Delete GrowerPurchase', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->grower_purchases_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GrowerPurchase', 'url'=>array('admin')),
);
?>
<div class="row">
	<div class="large-12 columns">
		
<h1>View GrowerPurchase #<?php echo $model->grower_purchases_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		//'grower_purchases_id',
		'grower_item_id',
		'propsed_quantity',
		'propsed_price',
		'proposed_delivery_date',
		'order_notes',
		'delivered_quantity',
		'final_price',
		'delivery_nots',
	),
)); ?>

	</div>
</div>