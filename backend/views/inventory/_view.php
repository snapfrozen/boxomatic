<?php
/* @var $this InventoryController */
/* @var $data Inventory */
$purchase = $data->supplierPurchase;
$product = $purchase->supplierProduct;
$extra = isset($updatedExtras[$data->supplier_product_id]) ? $updatedExtras[$data->supplier_product_id] : false;
?>
<div class="view large-12 columns end">
	<?php $form=$this->beginWidget('application.widgets.SnapActiveForm', array(
		'id'=>'extras-form-'.$data->inventory_id,
		'enableAjaxValidation'=>false,
	)); ?>
		<div class="image">
		<?php if(!empty($product->image)): ?>
			<?php echo CHtml::image($this->createUrl('supplierProduct/image',array('id'=>$product->id,'size'=>'tiny'))); ?>
		<?php else: ?>
			<?php echo CHtml::image($this->createUrl('supplierProduct/image',array('size'=>'tiny'))); ?>
		<?php endif; ?>
		</div>
		<div class="inner">
			<div class="row">
				<div class="large-9 columns">
					<h3><?php echo CHtml::encode($product->name); ?></h3>
					<span class="price">
						Price: <?php echo CHtml::encode(SnapFormat::currency($purchase->item_sales_price)); ?> 
						(<?php echo $product->unit ?>)
					</span>
					<?php if($data->showQuantity()): ?>
						<span class="stock">Stock left: <?php echo (int) $data->sum_quantity; ?></span>
					<?php endif; ?>
					<span class="description"><?php echo $product->description; ?></span>
				</div>
			
				<?php if(!$pastDeadline): ?>
				<div class="large-3 columns qty">
					<?php echo CHtml::dropDownList('supplier_purchases['.$purchase->id.']',1,Inventory::$quantityOptions); ?>
					<?php echo CHtml::submitButton('Add',array('class'=>'button tiny')); ?>
				</div>
				<?php endif; ?>
			</div>
			<?php if($extra) echo $form->errorSummary($extra,''); ?>
		</div>
	<?php $this->endWidget(); ?>
</div>