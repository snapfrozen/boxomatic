<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('box_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->box_id), array('view', 'id'=>$data->box_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('size_id')); ?>:</b>
	<?php echo CHtml::encode($data->size_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('box_price')); ?>:</b>
	<?php echo CHtml::encode($data->box_price); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('week_id')); ?>:</b>
	<?php echo CHtml::encode($data->week_id); ?>
	<br />


</div>