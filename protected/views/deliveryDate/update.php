<div class="row">
	
	<div class="large-12 columns">
		<h1>Update DeliveryDate <?php echo $model->id; ?></h1>
	</div>
	
	<div class="large-12 columns">
		<div class="panel">
			<?php /*echo CHtml::link('List DeliveryDate',array('deliveryDate/index'), array('class' => 'button small'))*/ ?>
			<?php echo CHtml::link('Create DeliveryDate',array('deliveryDate/create',), array('class' => 'button small')) ?>
			<?php echo CHtml::link('View DeliveryDate',array('deliveryDate/view', 'id' => $model->id), array('class' => 'button small')) ?>
			<?php echo CHtml::link('Delete DeliveryDate',array('deliveryDate/delete', 'id' => $model->id), array('class' => 'button small')) ?>
			<?php echo CHtml::link('Manage DeliveryDate',array('deliveryDate/admin'), array('class' => 'button small')) ?>
		</div>
	</div>

	<div class="large-12 columns">
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>

</div>