<?php
$this->menu=array(
	array('label'=>'List UserBox', 'url'=>array('index')),
	array('label'=>'Create UserBox', 'url'=>array('create')),
	array('label'=>'Update UserBox', 'url'=>array('update', 'id'=>$model->user_box_id)),
	array('label'=>'Delete UserBox', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->user_box_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UserBox', 'url'=>array('admin')),
);
?>

<h1>View Box</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'Customer.User.full_name',
		'Box.BoxSize.box_size_name',
		'Box.box_price',
		'Box.DeliveryDate.date',
		'quantity',
		'total_price',
	),
)); ?>

<h2>Items</h2>

<?php 
$this->widget('zii.widgets.CListView', array(
	'summaryText'=>'',
	'enablePagination'=>false,
	'enableSorting'=>false,
    'dataProvider'=>$items,
    'itemView'=>'../boxItem/_view',
));
?>