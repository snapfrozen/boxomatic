<div class="row">
	<div class="large-12 columns">
		<h1>Manage Box Sizes</h1>
	</div>
	<div class="large-12 columns">
		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'box-size-grid',
			'dataProvider'=>$model->search(),
			'cssFile' => '',
			'filter'=>$model,
			'columns'=>array(
				'box_size_name',
				'box_size_value',
				'box_size_markup',
				'box_size_price',
				array(
					'class'=>'application.components.snap.SnapButtonColumn',
					'template'=>'{update}',
				),
			),
		)); ?>
	</div>
</div>



