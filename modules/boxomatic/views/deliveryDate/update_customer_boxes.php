<?php
$this->menu=array(
	array('label'=>'List DeliveryDate', 'url'=>array('index')),
	array('label'=>'Create DeliveryDate', 'url'=>array('create')),
);
?>

<h1>Manage orders</h1>

<?php $this->widget('CEditableGridView', array(
	'id'=>'order-grid',
	'dataProvider'=>$CustBoxes,
	'showQuickBar'=>false,
	'quickUpdateAction'=>'custBoxUpdate',
	//'filter'=>$model,
	'columns'=>array(
		'Box.BoxSize.box_size_name',
		array(
			'name' => 'delivery_cost', 
			'sortable' => true,
			'class' => 'CEditableColumn'
		),
	),
)); ?>
