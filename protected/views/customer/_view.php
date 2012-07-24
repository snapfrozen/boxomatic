<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('customer_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->customer_id), array('view', 'id'=>$data->customer_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('customer_name')); ?>:</b>
	<?php echo CHtml::encode($data->customer_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('customer_phone')); ?>:</b>
	<?php echo CHtml::encode($data->customer_phone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('customer_mobile')); ?>:</b>
	<?php echo CHtml::encode($data->customer_mobile); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('customer_address')); ?>:</b>
	<?php echo CHtml::encode($data->customer_address); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('customer_address2')); ?>:</b>
	<?php echo CHtml::encode($data->customer_address2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('location_id')); ?>:</b>
	<?php echo CHtml::encode($data->location_id); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('customer_notes')); ?>:</b>
	<?php echo CHtml::encode($data->customer_notes); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('customer_email')); ?>:</b>
	<?php echo CHtml::encode($data->customer_email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('customer_password')); ?>:</b>
	<?php echo CHtml::encode($data->customer_password); ?>
	<br />

	*/ ?>

</div>