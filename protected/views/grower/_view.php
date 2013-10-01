<div class="large-6 columns">
	<div class="panel">
		<ul class='no-bullet'>
			<li><?php echo CHtml::encode($data->getAttributeLabel('grower_id')); ?> : <?php echo CHtml::link(CHtml::encode($data->grower_id), array('view', 'id'=>$data->grower_id)); ?></li>
			<li><?php echo CHtml::encode($data->getAttributeLabel('grower_website')); ?> : <?php echo CHtml::encode($data->grower_website); ?></li>
			<li><?php echo CHtml::encode($data->getAttributeLabel('grower_distance_kms')); ?> : <?php echo CHtml::encode($data->grower_distance_kms); ?></li>
			<li><?php echo CHtml::encode($data->getAttributeLabel('grower_bank_account_name')); ?> : <?php echo CHtml::encode($data->grower_bank_account_name); ?></li>
			<li><?php echo CHtml::encode($data->getAttributeLabel('grower_bank_bsb')); ?> : <?php echo CHtml::encode($data->grower_bank_bsb); ?></li>
			<li><?php echo CHtml::encode($data->getAttributeLabel('grower_bank_acc')); ?> : <?php echo CHtml::encode($data->grower_bank_acc); ?></li>
			<li><?php echo CHtml::encode($data->getAttributeLabel('grower_certification_status')); ?> : <?php echo CHtml::encode($data->grower_certification_status); ?></li>
		</ul>
	</div>
</div>
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