<div class="row">
	<div class="large-12 columns">
		<h1>Update Grower Item <?php echo $model->item_id; ?></h1>
	</div>
	<div class="large-12 columns">
		<div class="panel">
			<?php echo CHtml::link('List',array('growerItem/index'), array('class' => 'button small')); ?>
			<?php echo CHtml::link('Create',array('growerItem/create'), array('class' => 'button small')); ?>
			<?php echo CHtml::link('View',array('growerItem/view', 'id' => $model->item_id), array('class' => 'button small')); ?>
			<?php echo CHtml::link('Manage',array('growerItem/admin'), array('class' => 'button small')); ?>
		</div>
	</div>
	<div class="large-12 columns">
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>



