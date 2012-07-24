<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('item_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->item_id), array('view', 'id'=>$data->item_id)); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('grower_name')); ?>:</b>
	<?php echo CHtml::encode($data->grower->grower_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('item_name')); ?>:</b>
	<?php echo CHtml::encode($data->item_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('item_value')); ?>:</b>
	$<?php echo CHtml::encode($data->item_value); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('item_unit')); ?>:</b>
	<?php echo CHtml::encode($data->item_unit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('item_available_from')); ?>:</b>
	<?php echo CHtml::encode($data->item_available_from); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('item_available_to')); ?>:</b>
	<?php echo CHtml::encode($data->item_available_to); ?>
	<br />


</div>