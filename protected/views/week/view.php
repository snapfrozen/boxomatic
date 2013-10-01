
<div class="row">
	<div class="large-12 columns">
		<h1>View Week #<?php echo $model->week_id; ?></h1>
	</div>
	<div class="large-12 columns">
		<div class="panel">
			<?php /*echo CHtml::link('List Week',array('week/index'), array('class' => 'button small'))*/ ?>
			<?php echo CHtml::link('Create Week',array('week/create',), array('class' => 'button small')) ?>
			<?php echo CHtml::link('Update Week',array('week/update', 'id' => $model->week_id), array('class' => 'button small')) ?>
			<?php echo CHtml::link('Delete Week',array('week/delete', 'id' => $model->week_id), array('class' => 'button small')) ?>
			<?php echo CHtml::link('Manage Week',array('week/admin'), array('class' => 'button small')) ?>
		</div>
	</div>
	<div class="large-12 columns">

		<table>
			<tr>
				<td>Week Starting</td>
				<td><?php echo $model->week_delivery_date; ?></td>
			</tr>
			<tr>
				<td>Week Notes</td>
				<td><?php echo ($model->week_notes) ? $model->week_notes : 'Not Set'; ?></td>
			</tr>
			<tr>
				<td>Week Disabled</td>
				<td><?php echo $model->week_disabled; ?></td>
			</tr>
		</table>

		<?php /*$this->widget('zii.widgets.CDetailView', array(
			'data'=>$model,
			'attributes'=>array(
				'week_delivery_date',
				'week_notes',
				'week_disabled',
			),
		));*/ ?>
	</div>
</div>



