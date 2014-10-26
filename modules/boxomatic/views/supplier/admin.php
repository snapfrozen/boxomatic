<?php
$this->breadcrumbs=array(
	'Box-O-Matic'=>array('/snapcms/boxomatic/index'),
	'Suppliers',
);
$this->menu=array(
	array('icon' => 'glyphicon glyphicon-plus-sign', 'label'=>'Create Supplier', 'url'=>array('create')),
	array('icon' => 'glyphicon glyphicon-export', 'label'=>'Export Suppliers', 'url'=>array('export')),
);
$this->page_heading = 'Suppliers';
Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('supplier-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
Yii::app()->clientScript->registerScript('initPageSize',<<<EOD
    $('.change-pageSize').live('change', function() {
        $.fn.yiiGridView.update('supplier-grid',{ data:{ pageSize: $(this).val() }})
    });
EOD
,CClientScript::POS_READY);
?>
	<div class="large-12 columns search-form" style="display:none">
		<?php $this->renderPartial('_search',array(
			'model'=>$model,
		)); ?>
	</div>

<?php
$this->beginWidget('bootstrap.widgets.BsPanel', array(
	'title'=>'&nbsp;',
));
?>
<?php $dataProvider=$model->search(); ?>
<?php $pageSize=Yii::app()->user->getState('pageSize',10); ?>
<?php $this->widget('bootstrap.widgets.BsGridView', array(
	'id'=>'supplier-grid',
	'dataProvider'=>$dataProvider,
	'cssFile' => '', 
	'filter'=>$model,
	'summaryText'=>'Displaying {start}-{end} of {count} result(s). ' .
		CHtml::dropDownList(
			'pageSize',
			$pageSize,
			array(5=>5,10=>10,20=>20,50=>50,100=>100),
			array('class'=>'change-pageSize')) .
		' rows per page',
	'columns'=>array(
		'id',
		'name',
		'mobile',
		'phone',
		//'address',
		array(
			'name'=>'item_search',
			'value'=>'$data->supplier_products'
		),
		array(
			'class'=>'bootstrap.widgets.BsButtonColumn',
            'template' => '{update}{delete}{purchase}',
            'buttons' => array(
                'purchase' => array(
                    'url' => 'array("supplierPurchase/create","supplier"=>$data->id)',
                    'label'=>'<i class="glyphicon glyphicon glyphicon-list-alt"></i>',
                    'options'=>array('title'=>'Create Purchase'),
                ),
            )
		),
	),
)); ?>
<?php $this->endWidget(); ?>