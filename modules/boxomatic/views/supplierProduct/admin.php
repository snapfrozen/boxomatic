<?php
$this->breadcrumbs=array(
	'Box-O-Matic'=>array('/snapcms/boxomatic/index'),
	'Suppliers'=>array('supplier/admin'),
	'Products',
);
$this->menu=array(
	array('icon' => 'glyphicon glyphicon-plus-sign', 'label'=>'Add Product', 'url'=>array('create')),
	array('icon' => 'glyphicon glyphicon-list', 'label'=>'Categories', 'url'=>array('category/admin')),
);
$this->page_heading = 'Products';

Yii::app()->clientScript->registerScript('initPageSize',<<<EOD
	$('.change-pageSize').live('change', function() {
		$.fn.yiiGridView.update('supplier-item-grid',{ data:{ pageSize: $(this).val() }})
	});
EOD
,CClientScript::POS_READY);
?>
<?php
$this->beginWidget('bootstrap.widgets.BsPanel', array(
	'title'=>'&nbsp;',
));
?>
<?php $dataProvider=$model->search(); ?>
<?php $pageSize=Yii::app()->user->getState('pageSize',10); ?>
<?php $this->widget('backend.widgets.SnapGridView', array(
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
		'value:currency',
		'unit',
		array( 'name'=>'available_from', 'value'=>'SnapFormat::getMonthName($data->available_from)' ),
		array( 'name'=>'available_to', 'value'=>'SnapFormat::getMonthName($data->available_to)' ),
		/*
		'available_to',
		*/
		array(
			'class'=>'bootstrap.widgets.BsButtonColumn',
		),
	),
)); ?>
<?php $this->endWidget(); ?>