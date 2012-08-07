<?php
$this->menu=array(
	array('label'=>'List CustomerBox', 'url'=>array('index')),
	array('label'=>'Create CustomerBox', 'url'=>array('create')),
	array('label'=>'Update CustomerBox', 'url'=>array('update', 'id'=>$model->customer_box_id)),
	array('label'=>'Delete CustomerBox', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->customer_box_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CustomerBox', 'url'=>array('admin')),
);
?>

<h1>View Box</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'Customer.User.user_name',
		'Box.BoxSize.box_size_name',
		'Box.box_price',
		'Box.Week.week_starting',
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