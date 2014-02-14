<?php

/*$this->menu=array(
	array('label'=>'Create Inventory', 'url'=>array('create')),
);*/

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('supplier-item-grid', {
		data: $(this).serialize()
	});
	return false;
});
");

Yii::app()->clientScript->registerScript('initPageSize',<<<EOD
	$('.change-pageSize').live('change', function() {
		$.fn.yiiGridView.update('supplier-item-grid',{ data:{ pageSize: $(this).val() }})
	});
EOD
,CClientScript::POS_READY);

?>

<h1>Supplier Products</h1>
<div class="panel">
	<?php echo CHtml::link('Add Product',array('supplierProduct/create'),array('class'=>'button small')) ?>
	<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button button small')); ?>
</div>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div>

<?php $dataProvider=$model->search(); ?>
<?php $pageSize=Yii::app()->user->getState('pageSize',10); ?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'supplier-item-grid',
	'cssFile' => '', 
	'dataProvider'=>$dataProvider,
	'filter'=>$model,
	'summaryText'=>'Displaying {start}-{end} of {count} result(s). ' .
	CHtml::dropDownList(
		'pageSize',
		$pageSize,
		array(5=>5,10=>10,20=>20,50=>50,100=>100),
		array('class'=>'change-pageSize')) .
	' rows per page',
	'columns'=>array(
		array( 
			'name'=>'supplier_search', 
			'value'=>'$data->Supplier->name',
			'visible'=>Yii::app()->user->checkAccess('Admin'),
		),
		'name',
		'value',
		'unit',
		array( 'name'=>'available_from', 'value'=>'Yii::app()->snapFormat->getMonthName($data->available_from)' ),
		array( 'name'=>'available_to', 'value'=>'Yii::app()->snapFormat->getMonthName($data->available_to)' ),
		/*
		'available_to',
		*/
		array(
			'class'=>'application.components.snap.SnapButtonColumn',
		),
	),
)); ?>

