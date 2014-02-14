<div class="row">
	<div class="large-12 columns">
		<h1>Manage Locations</h1>
	</div>
	<div class="large-12 columns">
		<div class="panel">
			<?php echo CHtml::link('Create location',array('create'), array('class' => 'button small')); ?>
		</div>
	</div>

	<div class="large-12 columns">
		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'location-grid',
			'cssFile' => '',
			'dataProvider'=>$model->search(),
			'filter'=>$model,
			'columns'=>array(
				'location_name',
				'location_delivery_value',
				'is_pickup',
				array(
					'class'=>'application.components.snap.SnapButtonColumn',
				),
			),
		)); ?>
	</div>
</div>


