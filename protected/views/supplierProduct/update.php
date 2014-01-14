<div class="row">
	<div class="large-12 columns">
		<h1>Update Supplier Item <?php echo $model->id; ?></h1>
	</div>
	<div class="large-12 columns">
		<div class="panel">
			<?php echo CHtml::link('List',array('supplierProduct/index'), array('class' => 'button small')); ?>
			<?php echo CHtml::link('Create',array('supplierProduct/create'), array('class' => 'button small')); ?>
			<?php echo CHtml::link('View',array('supplierProduct/view', 'id' => $model->id), array('class' => 'button small')); ?>
			<?php echo CHtml::link('Manage',array('supplierProduct/admin'), array('class' => 'button small')); ?>
		</div>
	</div>
	<div class="large-12 columns">
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>



