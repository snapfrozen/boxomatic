<?php
/* @var $this CustomerLocationController */
/* @var $model CustomerLocation */
?>

<div class="view">
	
	<?php echo CHtml::link('Update', array('customerLocation/update', 'id'=>$data->customer_location_id),array('class'=>'update')); ?>
<!--	<?php echo CHtml::link('Delete', array('customerLocation/delete', 'id'=>$data->customer_location_id),array('class'=>'update','confirm'=>'Are you sure you want to delete this address?')); ?> -->
	
	<p><b><?php echo $data->Location->location_name; ?></b></p>

	<b><?php echo CHtml::encode($data->getAttributeLabel('address')); ?></b><br />
	<?php echo CHtml::encode($data->address); ?>
	<br />

	<?php if(!empty($data->address2)): ?>
		<?php echo CHtml::encode($data->address2); ?>
		<br />
	<?php endif; ?>

	<?php echo CHtml::encode($data->suburb); ?>
	<br />

	<?php echo CHtml::encode($data->state); ?> <?php echo CHtml::encode($data->postcode); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('phone')); ?>:</b>
	<?php echo CHtml::encode($data->phone); ?>
	<br />

</div>