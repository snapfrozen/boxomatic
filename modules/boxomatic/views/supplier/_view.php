<div class="large-6 columns">
	<div class="panel">
		<ul class='no-bullet'>
			<li><?php echo CHtml::encode($data->getAttributeLabel('id')); ?> : <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?></li>
			<li><?php echo CHtml::encode($data->getAttributeLabel('website')); ?> : <?php echo CHtml::encode($data->website); ?></li>
			<li><?php echo CHtml::encode($data->getAttributeLabel('distance_kms')); ?> : <?php echo CHtml::encode($data->distance_kms); ?></li>
			<li><?php echo CHtml::encode($data->getAttributeLabel('bank_account_name')); ?> : <?php echo CHtml::encode($data->bank_account_name); ?></li>
			<li><?php echo CHtml::encode($data->getAttributeLabel('bank_bsb')); ?> : <?php echo CHtml::encode($data->bank_bsb); ?></li>
			<li><?php echo CHtml::encode($data->getAttributeLabel('bank_acc')); ?> : <?php echo CHtml::encode($data->bank_acc); ?></li>
			<li><?php echo CHtml::encode($data->getAttributeLabel('certification_status')); ?> : <?php echo CHtml::encode($data->certification_status); ?></li>
		</ul>
	</div>
</div>
<?php /*
<b><?php echo CHtml::encode($data->getAttributeLabel('order_days')); ?>:</b>
<?php echo CHtml::encode($data->order_days); ?>
<br />

<b><?php echo CHtml::encode($data->getAttributeLabel('produce')); ?>:</b>
<?php echo CHtml::encode($data->produce); ?>
<br />

<b><?php echo CHtml::encode($data->getAttributeLabel('notes')); ?>:</b>
<?php echo CHtml::encode($data->notes); ?>
<br />

<b><?php echo CHtml::encode($data->getAttributeLabel('payment_details')); ?>:</b>
<?php echo CHtml::encode($data->payment_details); ?>
<br />

*/ ?>