<h1>Manage Box Sizes</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'box-size-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'box_size_name',
		'box_size_value',
		'box_size_markup',
		'box_size_price',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}',
		),
	),
)); ?>
