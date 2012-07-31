<?php
$this->breadcrumbs=array(
	'Boxes'=>array('index'),
	$model->BoxSize->box_size_name,
);

$this->menu=array(
	array('label'=>'List Box', 'url'=>array('index')),
	array('label'=>'Create Box', 'url'=>array('create')),
	array('label'=>'Update Box', 'url'=>array('update', 'id'=>$model->box_id)),
	array('label'=>'Delete Box', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->box_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Box', 'url'=>array('admin')),
);
?>

<h1><?php echo $model->BoxSize->box_size_name; ?> Box</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'BoxSize.box_size_name',
		'box_price',
		'Week.week_num',
	),
)); ?>

<h2>Items</h2>

<?php 

$this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$items,
    'itemView'=>'../boxItem/_view',
));

?>