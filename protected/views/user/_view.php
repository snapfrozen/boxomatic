<div class="large-4 columns">
	<div class="panel">
		<ul class='no-bullet'>
			<li><?php echo CHtml::encode($data->getAttributeLabel('id')); ?> : <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?></li>
			<li><?php echo CHtml::encode($data->getAttributeLabel('customer_id')); ?> : <?php echo CHtml::encode($data->customer_id); ?></li>
			<li><?php echo CHtml::encode($data->getAttributeLabel('supplier_id')); ?> : <?php echo CHtml::encode($data->supplier_id); ?></li>
			<li><?php echo CHtml::encode($data->getAttributeLabel('user_email')); ?> : <?php echo CHtml::encode($data->user_email); ?></li>
			<!-- <li><?php echo CHtml::encode($data->getAttributeLabel('password')); ?> : <?php echo CHtml::encode($data->password); ?></li> -->
			<li><?php echo CHtml::encode($data->getAttributeLabel('full_name')); ?> : <?php echo CHtml::encode($data->full_name); ?></li>
			<li><?php echo CHtml::encode($data->getAttributeLabel('user_phone')); ?> : <?php echo CHtml::encode($data->user_phone); ?></li>
		</ul>
	</div>
</div>
<?php /*
<b><?php echo CHtml::encode($data->getAttributeLabel('user_mobile')); ?>:</b>
<?php echo CHtml::encode($data->user_mobile); ?>
<br />

<b><?php echo CHtml::encode($data->getAttributeLabel('user_address')); ?>:</b>
<?php echo CHtml::encode($data->user_address); ?>
<br />

<b><?php echo CHtml::encode($data->getAttributeLabel('user_address2')); ?>:</b>
<?php echo CHtml::encode($data->user_address2); ?>
<br />

<b><?php echo CHtml::encode($data->getAttributeLabel('user_suburb')); ?>:</b>
<?php echo CHtml::encode($data->user_suburb); ?>
<br />

<b><?php echo CHtml::encode($data->getAttributeLabel('user_state')); ?>:</b>
<?php echo CHtml::encode($data->user_state); ?>
<br />

<b><?php echo CHtml::encode($data->getAttributeLabel('user_postcode')); ?>:</b>
<?php echo CHtml::encode($data->user_postcode); ?>
<br />

<b><?php echo CHtml::encode($data->getAttributeLabel('last_login_time')); ?>:</b>
<?php echo CHtml::encode($data->last_login_time); ?>
<br />

<b><?php echo CHtml::encode($data->getAttributeLabel('update_time')); ?>:</b>
<?php echo CHtml::encode($data->update_time); ?>
<br />

<b><?php echo CHtml::encode($data->getAttributeLabel('update_user_id')); ?>:</b>
<?php echo CHtml::encode($data->update_user_id); ?>
<br />

<b><?php echo CHtml::encode($data->getAttributeLabel('create_time')); ?>:</b>
<?php echo CHtml::encode($data->create_time); ?>
<br />

<b><?php echo CHtml::encode($data->getAttributeLabel('create_user_id')); ?>:</b>
<?php echo CHtml::encode($data->create_user_id); ?>
<br />

*/ ?>
