<?php
$this->breadcrumbs=array(
	'Box-O-Matic'=>array('/snapcms/boxomatic/index'),
	'Delivery Dates',
);
$this->page_heading = 'Delivery Dates';
?>

<?php
$this->beginWidget('bootstrap.widgets.BsPanel', array(
	'title'=>'&nbsp;',
));
?>
	<?php $this->widget('bootstrap.widgets.BsGridView', array(
		'id'=>'delivery-date-grid',
		'cssFile' => '', 'cssFile' => '', 
		'dataProvider'=>$model->search(),
		'filter'=>$model,
		'columns'=>array(
			'date',
			'notes',
			array(
				'name'=>'disabled',
				'type'=>'boolean',
				'filter'=>array(1=>'Yes',0=>'No')
			),
			array(
				'class'=>'bootstrap.widgets.BsButtonColumn',
				'template'=>'{update}{delete}'
			),
		),
	)); ?>
<?php $this->endWidget(); ?>

