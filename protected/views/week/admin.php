<h1>Manage Weeks</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'week-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'week_delivery_date',
		'week_notes',
		'week_disabled',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
