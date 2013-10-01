<div class="row">
	<div class="large-12 columns">
		<h1>Locations</h1>
	</div>
	<div class="large-12 columns">
		<div class="panel">
			<?php echo CHtml::link('Create Location', array('location/create'), array('class'=>'button small') ); ?>
			<?php echo CHtml::link('Manage Location', array('location/admin'), array('class'=>'button small') ); ?>
		</div>
	</div>
	<table>
		<thead>
			<tr>
				<th>Location</th>
				<th>Delivery Location</th>
				<th>Delivery</th>
			</tr>
		</thead>
		<tbody>
			<?php $this->widget('zii.widgets.CListView', array(
				'dataProvider'=>$dataProvider,
				'itemView'=>'_view',
			)); ?>
		</tbody>
	</table>
</div>

