<h1>Manage Locations</h1>

<p><?php echo CHtml::link('Create location',array('create')); ?></p>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'location-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'location_name',
		'location_delivery_value',
		'is_pickup',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
