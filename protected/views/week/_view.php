<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('week_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->week_id), array('view', 'id'=>$data->week_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('week_num')); ?>:</b>
	<?php echo CHtml::encode($data->week_num); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('week_notes')); ?>:</b>
	<?php echo CHtml::encode($data->week_notes); ?>
	<br />


</div>