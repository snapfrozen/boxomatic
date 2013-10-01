
<div class="row">
	<div class="large-12 columns">
		<h1>Manage Weeks</h1>
	</div>
	<div class="large-12 columns">
		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'week-grid',
			'cssFile' => '', 'cssFile' => '', 
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
	</div>
</div>


