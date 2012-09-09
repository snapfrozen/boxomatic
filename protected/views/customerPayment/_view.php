<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('payment_value')); ?>:</b>
	<?php echo CHtml::encode($data->payment_value); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('payment_type')); ?>:</b>
	<?php echo CHtml::encode($data->payment_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('payment_date')); ?>:</b>
	<?php echo CHtml::encode($data->payment_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('customer_id')); ?>:</b>
	<?php echo CHtml::encode($data->customer_id); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('payment_note')); ?>:</b>
	<?php echo CHtml::encode($data->payment_note); ?>
	<br />

</div>