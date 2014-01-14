
<div class="row">
	<div class="large-12 columns">
		<h1>Manage DeliveryDates</h1>
	</div>
	<div class="large-12 columns">
		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'delivery-date-grid',
			'cssFile' => '', 'cssFile' => '', 
			'dataProvider'=>$model->search(),
			'filter'=>$model,
			'columns'=>array(
				'date',
				'notes',
				'disabled',
				array(
					'class'=>'CButtonColumn',
				),
			),
		)); ?>
	</div>
</div>


