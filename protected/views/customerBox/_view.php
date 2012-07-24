<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('customer_box_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->customer_box_id), array('view', 'id'=>$data->customer_box_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('customer_id')); ?>:</b>
	<?php echo CHtml::encode($data->customer_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('box_id')); ?>:</b>
	<?php echo CHtml::encode($data->box_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('quantity')); ?>:</b>
	<?php echo CHtml::encode($data->quantity); ?>
	<br />


</div>