<?php
$this->breadcrumbs=array(
	'Box-O-Matic'=>array('/snapcms/boxomatic/index'),
	'Suppliers'=>array('supplier/admin'),
	'Purchases',
);
$this->menu=array(
	//array('icon' => 'glyphicon glyphicon-plus-sign', 'label'=>'Create Purchase', 'url'=>array('create')),
	array('icon' => 'glyphicon glyphicon-stats', 'label'=>'Reports', 'url'=>array('report')),
);
$this->page_heading = 'Purchases';
?>
<?php
$this->beginWidget('bootstrap.widgets.BsPanel', array(
	'title'=>'&nbsp;',
));
?>
<?php $this->widget('backend.widgets.SnapGridView', array(
	'id'=>'supplier-purchase-grid',
	'cssFile' => '', 
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
        /*
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
         */
        'Supplier.name',
		'delivery_date_formatted',
        array(
            'name' => 'total',
            'value' => 'SnapFormat::currency($data->total)',
        ),
		//'order_notes',
		/*
		'delivered_quantity',
		'final_price',
		'delivery_notes',
		*/
		array(
			'class'=>'bootstrap.widgets.BsButtonColumn',
			'template'=>'{view}{update}{duplicate}{delete}',
			'buttons'=>array(
				'duplicate' => array
				(
					'label' => '<i class="glyphicon glyphicon-keys"></i>',
					'options' => array('title'=>'Duplicate'),
					'url'=> 'array("supplierPurchase/duplicate","id"=>$data->id)',
				),
			)
		),
	),
)); ?>
<?php $this->endWidget(); ?>