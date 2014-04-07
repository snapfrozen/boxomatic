<?php
/* @var $this UserLocationController */
/* @var $model UserLocation */
?>

<div class="view col-md-4">
	
	<div class="box">
		<div class="actions">
			<?php echo CHtml::link('<i class="glyphicon glyphicon-pencil"></i>', array('userLocation/update', 'id'=>$data->customer_location_id),array('class'=>'update','title'=>'Update')); ?>
			<?php echo CHtml::link('<i class="glyphicon glyphicon-trash"></i>', array('userLocation/delete', 'id'=>$data->customer_location_id),array('class'=>'update','confirm'=>'Are you sure you want to delete this address?','title'=>'Delete')); ?>
		</div>

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

</div>