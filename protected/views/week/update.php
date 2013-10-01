<div class="row">
	
	<div class="large-12 columns">
		<h1>Update Week <?php echo $model->week_id; ?></h1>
	</div>
	
	<div class="large-12 columns">
		<div class="panel">
			<?php /*echo CHtml::link('List Week',array('week/index'), array('class' => 'button small'))*/ ?>
			<?php echo CHtml::link('Create Week',array('week/create',), array('class' => 'button small')) ?>
			<?php echo CHtml::link('View Week',array('week/view', 'id' => $model->week_id), array('class' => 'button small')) ?>
			<?php echo CHtml::link('Delete Week',array('week/delete', 'id' => $model->week_id), array('class' => 'button small')) ?>
			<?php echo CHtml::link('Manage Week',array('week/admin'), array('class' => 'button small')) ?>
		</div>
	</div>

	<div class="large-12 columns">
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>

</div>