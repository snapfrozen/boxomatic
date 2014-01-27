<?php
/* @var $this InventoryController */
/* @var $data Inventory */
$purchase = $data->supplierPurchase;
$product = $purchase->supplierProduct;
$extra = isset($updatedExtras[$data->supplier_product_id]) ? $updatedExtras[$data->supplier_product_id] : false;
?>
<div class="view large-4 columns end">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'extras-form-'.$data->inventory_id,
		'enableAjaxValidation'=>false,
	)); ?>
		<h3><?php echo CHtml::encode($product->name); ?></h3>
		<div class="image">
		<?php if(!empty($product->image)): ?>
			<?php echo CHtml::image($this->createUrl('supplierProduct/image',array('id'=>$product->id))); ?>
		<?php else: ?>
			<p>No image</p>
		<?php endif; ?>
		</div>
		<span class="price">Price: <?php echo CHtml::encode(Yii::app()->snapFormat->currency($purchase->item_sales_price)); ?></span>
		<?php if($data->showQuantity()): ?>
			<span class="stock">Stock left: <?php echo (int) $data->sum_quantity; ?></span>
		<?php endif; ?>
		<span class="description"><?php echo $product->description; ?></span>
		<?php echo CHtml::dropDownList('supplier_purchases['.$purchase->id.']',1,Inventory::$quantityOptions); ?>
		<?php echo CHtml::submitButton('Add',array('class'=>'button small')); ?>
		<?php if($extra) echo $form->errorSummary($extra,''); ?>
	<?php $this->endWidget(); ?>
</div>