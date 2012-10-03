<?php
$this->menu=array(
	array('label'=>'Create Inventory', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('grower-item-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Inventory</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'grower-item-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
        array( 
			'name'=>'grower_search', 
			'value'=>'$data->Grower->grower_name',
			'visible'=>Yii::app()->user->checkAccess('admin'),
		),
		'item_name',
		'item_value',
		'item_unit',
		array( 'name'=>'item_available_from', 'value'=>'Yii::app()->snapFormat->getMonthName($data->item_available_from)' ),
		array( 'name'=>'item_available_to', 'value'=>'Yii::app()->snapFormat->getMonthName($data->item_available_to)' ),
		/*
		'item_available_to',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
