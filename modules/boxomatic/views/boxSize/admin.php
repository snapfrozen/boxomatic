<?php
$this->breadcrumbs=array(
	'Box-O-Matic'=>array('/snapcms/boxomatic/index'),
	'Box Sizes',
);
$this->menu=array(
	array('icon' => 'glyphicon glyphicon-plus-sign', 'label'=>'Create Box Size', 'url'=>array('boxSize/create')),
);
$this->page_heading = 'Box Sizes';
?>

<?php
$this->beginWidget('bootstrap.widgets.BsPanel', array(
	'title'=>'&nbsp;',
));
?>
<?php $this->widget('application.widgets.SnapGridView', array(
	'id'=>'box-size-grid',
	'dataProvider'=>$model->search(),
	'cssFile' => '',
	'filter'=>$model,
	'columns'=>array(
		'box_size_name',
		'box_size_value:currency',
		'box_size_markup:currency',
		'box_size_price:currency',
		array(
			'class'=>'bootstrap.widgets.BsButtonColumn',
			'template'=>'{update}',
		),
	),
)); ?>
<?php $this->endWidget(); ?>
