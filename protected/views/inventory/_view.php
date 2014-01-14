<?php
/* @var $this InventoryController */
/* @var $data Inventory */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('inventory_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->inventory_id), array('view', 'id'=>$data->inventory_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('grower_purchase_id')); ?>:</b>
	<?php echo CHtml::encode($data->grower_purchase_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('quantity')); ?>:</b>
	<?php echo CHtml::encode($data->quantity); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('price')); ?>:</b>
	<?php echo CHtml::encode($data->price); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('box_reserve')); ?>:</b>
	<?php echo CHtml::encode($data->box_reserve); ?>
	<br />


</div>