
<div class="row">
	<div class="large-12 columns">
		<h1>View DeliveryDate #<?php echo $model->id; ?></h1>
	</div>
	<div class="large-12 columns">
		<div class="panel">
			<?php /*echo CHtml::link('List DeliveryDate',array('deliveryDate/index'), array('class' => 'button small'))*/ ?>
			<?php echo CHtml::link('Create DeliveryDate',array('deliveryDate/create',), array('class' => 'button small')) ?>
			<?php echo CHtml::link('Update DeliveryDate',array('deliveryDate/update', 'id' => $model->id), array('class' => 'button small')) ?>
			<?php echo CHtml::link('Delete DeliveryDate',array('deliveryDate/delete', 'id' => $model->id), array('class' => 'button small')) ?>
			<?php echo CHtml::link('Manage DeliveryDate',array('deliveryDate/admin'), array('class' => 'button small')) ?>
		</div>
	</div>
	<div class="large-12 columns">

		<table>
			<tr>
				<td>DeliveryDate Starting</td>
				<td><?php echo $model->date; ?></td>
			</tr>
			<tr>
				<td>DeliveryDate Notes</td>
				<td><?php echo ($model->notes) ? $model->notes : 'Not Set'; ?></td>
			</tr>
			<tr>
				<td>DeliveryDate Disabled</td>
				<td><?php echo $model->disabled; ?></td>
			</tr>
		</table>

		<?php /*$this->widget('zii.widgets.CDetailView', array(
			'data'=>$model,
			'attributes'=>array(
				'date',
				'notes',
				'disabled',
			),
		));*/ ?>
	</div>
</div>



