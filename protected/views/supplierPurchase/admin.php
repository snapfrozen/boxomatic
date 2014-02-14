<?php
/* @var $this SupplierPurchaseController */
/* @var $model SupplierPurchase */

$this->breadcrumbs=array(
	'Supplier Purchases'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List SupplierPurchase', 'url'=>array('index')),
	array('label'=>'Create SupplierPurchase', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('supplier-purchase-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div class="row">
	<div class="large-12 columns">
		
<h1>Manage Supplier Purchases</h1>

<?php echo CHtml::link('Add',array('create'),array('class'=>'button small')) ?>&nbsp;
<?php echo CHtml::link('Reports',array('report'),array('class'=>'button small')) ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'supplier-purchase-grid',
	'cssFile' => '', 
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		array(
			'name' => 'supplier_cert_search',
			'type'=>'raw',
			'value' => 'CHtml::value($data,"supplierProduct.Supplier.certification_status")',
		),
		array(
			'name' => 'item_name_search',
			'type'=>'raw',
			'value' => 'CHtml::value($data,"supplierProduct.name_with_unit")',
		),
		array(
			'name' => 'supplier_name_search',
			'type'=>'raw',
			'value' => 'CHtml::value($data,"supplierProduct.Supplier.name")',
		),
		'propsed_quantity',
		'propsed_price',
		'proposed_delivery_date_formatted',
		//'order_notes',
		/*
		'delivered_quantity',
		'final_price',
		'delivery_notes',
		*/
		array(
			'class'=>'application.components.snap.SnapButtonColumn',
			'template'=>'{view}{update}{duplicate}{delete}',
			'buttons'=>array(
				'duplicate' => array
				(
					'label' => '<i class="fi fi-page-copy"></i>',
					'options' => array('title'=>'Duplicate'),
					'url'=> 'array("supplierPurchase/duplicate","id"=>$data->id)',
				),
			)
		),
	),
)); ?>

	</div>
</div>