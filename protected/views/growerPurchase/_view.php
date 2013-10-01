<?php
/* @var $this GrowerPurchaseController */
/* @var $model GrowerPurchase */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('grower_purchases_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->grower_purchases_id), array('view', 'id'=>$data->grower_purchases_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('grower_item_id')); ?>:</b>
	<?php echo CHtml::encode($data->grower_item_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('propsed_quantity')); ?>:</b>
	<?php echo CHtml::encode($data->propsed_quantity); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('propsed_price')); ?>:</b>
	<?php echo CHtml::encode($data->propsed_price); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('proposed_delivery_date')); ?>:</b>
	<?php echo CHtml::encode($data->proposed_delivery_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('order_notes')); ?>:</b>
	<?php echo CHtml::encode($data->order_notes); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('delivered_quantity')); ?>:</b>
	<?php echo CHtml::encode($data->delivered_quantity); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('final_price')); ?>:</b>
	<?php echo CHtml::encode($data->final_price); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('delivery_nots')); ?>:</b>
	<?php echo CHtml::encode($data->delivery_nots); ?>
	<br />

	*/ ?>

</div>