<?php
/* @var $this GrowerPurchaseController */
/* @var $model GrowerPurchase */

$this->breadcrumbs=array(
	'Grower Purchases'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List GrowerPurchase', 'url'=>array('index')),
	array('label'=>'Create GrowerPurchase', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('grower-purchase-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div class="row">
	<div class="large-12 columns">
		
<h1>Manage Grower Purchases</h1>

<?php echo CHtml::link('Add',array('create'),array('class'=>'button small')) ?>&nbsp;
<?php echo CHtml::link('Reports',array('report'),array('class'=>'button small')) ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'grower-purchase-grid',
	'cssFile' => '', 
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'grower_purchases_id',
		array(
			'name' => 'grower_cert_search',
			'type'=>'raw',
			'value' => 'CHtml::value($data,"growerItem.Grower.grower_certification_status")',
		),
		array(
			'name' => 'item_name_search',
			'type'=>'raw',
			'value' => 'CHtml::value($data,"growerItem.item_name_with_unit")',
		),
		array(
			'name' => 'grower_name_search',
			'type'=>'raw',
			'value' => 'CHtml::value($data,"growerItem.Grower.grower_name")',
		),
		'propsed_quantity',
		'propsed_price',
		'proposed_delivery_date_formatted',
		//'order_notes',
		/*
		'delivered_quantity',
		'final_price',
		'delivery_nots',
		*/
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}{update}{delete}{duplicate}',
			'buttons'=>array(
				'duplicate' => array
				(
					'url'=> 'array("growerPurchase/duplicate","id"=>$data->grower_purchases_id)',
					'options'=>array('class'=>'text'),
				),
			)
		),
	),
)); ?>

	</div>
</div>