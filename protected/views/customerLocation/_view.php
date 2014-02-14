<?php
/* @var $this CustomerLocationController */
/* @var $model CustomerLocation */
?>

<div class="view">
	
	<?php echo CHtml::link('<i class="fi fi-x"></i>', array('customerLocation/delete', 'id'=>$data->customer_location_id),array('class'=>'update','confirm'=>'Are you sure you want to delete this address?','title'=>'Delete')); ?>
	<?php echo CHtml::link('<i class="fi fi-page-edit"></i>', array('customerLocation/update', 'id'=>$data->customer_location_id),array('class'=>'update','title'=>'Update')); ?>
	
	<p><b><?php echo $data->Location->location_name; ?></b></p>

	<b><?php echo CHtml::encode($data->getAttributeLabel('address')); ?>:</b><br />
	
	<p>
		<?php echo CHtml::encode($data->address); ?>
		<br />
		<?php echo CHtml::encode($data->suburb); ?>
		<br />
		<?php echo CHtml::encode($data->state); ?> <?php echo CHtml::encode($data->postcode); ?>
	</p>

	<p>
		<b><?php echo CHtml::encode($data->getAttributeLabel('phone')); ?>:</b>
		<?php echo CHtml::encode($data->phone); ?>
	</p>

</div>