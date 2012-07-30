<?php
$this->menu=array(
	array('label'=>'List GrowerItem', 'url'=>array('index')),
	array('label'=>'Create GrowerItem', 'url'=>array('create')),
	array('label'=>'Update GrowerItem', 'url'=>array('update', 'id'=>$model->item_id)),
	array('label'=>'Delete GrowerItem', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->item_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GrowerItem', 'url'=>array('admin')),
);
?>

<h1><?php echo $model->item_name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'Grower.grower_name',
		'item_name',
		'item_value',
		'item_unit',
		array( 'name'=>'item_available_from', 'value'=>Yii::app()->snapFormat->getMonthName($model->item_available_from) ),
		array( 'name'=>'item_available_to', 'value'=>Yii::app()->snapFormat->getMonthName($model->item_available_to) ),
	),
)); ?>
