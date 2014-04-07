<?php
/* @var $this SupplierPurchaseController */
/* @var $model SupplierPurchase */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('supplier_product_id')); ?>:</b>
	<?php echo CHtml::encode($data->supplier_product_id); ?>
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

	<b><?php echo CHtml::encode($data->getAttributeLabel('delivery_notes')); ?>:</b>
	<?php echo CHtml::encode($data->delivery_notes); ?>
	<br />

	*/ ?>

</div>