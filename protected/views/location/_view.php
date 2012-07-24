<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('location_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->location_id), array('view', 'id'=>$data->location_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('location_name')); ?>:</b>
	<?php echo CHtml::encode($data->location_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('location_delivery_value')); ?>:</b>
	<?php echo CHtml::encode($data->location_delivery_value); ?>
	<br />


</div>