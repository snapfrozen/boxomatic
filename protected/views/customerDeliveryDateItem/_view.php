<?php
/* @var $this CustomerDeliveryDateItemController */
/* @var $data CustomerDeliveryDateItem */
$purchase = $data->supplierPurchase;
$product = $purchase->supplierProduct;
$extra = isset($updatedOrders[$data->id]) ? $updatedOrders[$data->id] : false;
?>

<div class="view large-12 columns end">
	<div class="row">
		<?php if($extra) echo $form->errorSummary($extra,''); ?>
		<div class="large-4 columns">
			<div class="image">
			<?php if(!empty($product->image)): ?>
				<?php echo CHtml::image($this->createUrl('supplierProduct/image',array('id'=>$product->id))); ?>
			<?php else: ?>
				<p>No image</p>
			<?php endif; ?>
			</div>
		</div>
		<div class="large-8 columns">
			<h3><?php echo CHtml::encode($product->name); ?></h3>
			<span class="price"><?php echo CHtml::encode(Yii::app()->snapFormat->currency($data->total)); ?> <span class="each">(<?php echo Yii::app()->snapFormat->currency($purchase->item_sales_price) ?> ea.)<span></span>
			<?php echo $form->dropDownList($data, 'quantity', Inventory::$quantityOptions, array('name'=>'extras['.$data->id.']')); ?>
			<?php //echo CHtml::link('Remove',array('removeProduct','id'=>$data->id),array('class'=>'button small')) ?>
			<?php echo CHtml::submitButton('Update',array('class'=>'button small')); ?>
		</div>
	</div>
</div>