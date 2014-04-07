<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_box_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->user_box_id), array('view', 'id'=>$data->user_box_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('box_id')); ?>:</b>
	<?php echo CHtml::encode($data->box_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('quantity')); ?>:</b>
	<?php echo CHtml::encode($data->quantity); ?>
	<br />


</div>