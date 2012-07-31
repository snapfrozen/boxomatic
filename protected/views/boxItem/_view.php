<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('box_item_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->box_item_id), array('view', 'id'=>$data->box_item_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('item_name')); ?>:</b>
	<?php echo CHtml::encode($data->item_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('box_id')); ?>:</b>
	<?php echo CHtml::encode($data->box_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('item_value')); ?>:</b>
	<?php echo CHtml::encode($data->item_value); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('grower_id')); ?>:</b>
	<?php echo CHtml::encode($data->grower_id); ?>
	<br />


</div>