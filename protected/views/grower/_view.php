<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('grower_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->grower_id), array('view', 'id'=>$data->grower_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('grower_website')); ?>:</b>
	<?php echo CHtml::encode($data->grower_website); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('grower_distance_kms')); ?>:</b>
	<?php echo CHtml::encode($data->grower_distance_kms); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('grower_bank_account_name')); ?>:</b>
	<?php echo CHtml::encode($data->grower_bank_account_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('grower_bank_bsb')); ?>:</b>
	<?php echo CHtml::encode($data->grower_bank_bsb); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('grower_bank_acc')); ?>:</b>
	<?php echo CHtml::encode($data->grower_bank_acc); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('grower_certification_status')); ?>:</b>
	<?php echo CHtml::encode($data->grower_certification_status); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('grower_order_days')); ?>:</b>
	<?php echo CHtml::encode($data->grower_order_days); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('grower_produce')); ?>:</b>
	<?php echo CHtml::encode($data->grower_produce); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('grower_notes')); ?>:</b>
	<?php echo CHtml::encode($data->grower_notes); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('grower_payment_details')); ?>:</b>
	<?php echo CHtml::encode($data->grower_payment_details); ?>
	<br />

	*/ ?>

</div>