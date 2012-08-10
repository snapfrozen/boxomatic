<?php
$this->menu=array(
	array('label'=>'List Box', 'url'=>array('index')),
	array('label'=>'Create Box', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('box-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Boxes</h1>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'box-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'BoxSize.box_size_name',
		'box_price',
		'Week.week_delivery_date',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
