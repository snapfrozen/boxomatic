<?php
/* @var $this OrderItemController */
/* @var $data OrderItem */
$purchase = $data->supplierPurchase;
$product = $purchase->supplierProduct;
$extra = isset($updatedOrders[$data->id]) ? $updatedOrders[$data->id] : false;
?>

<div class="view large-12 columns end">
	<div class="row">
		<?php if($extra) echo $form->errorSummary($extra,''); ?>
		<div class="large-4 columns">
			<div class="image">
				<?php echo SnapHtml::image($product, 'image', array('w'=>70,'h'=>70,'zc'=>1)) ?>
			</div>
		</div>
		<div class="large-8 columns">
			<h3><?php echo CHtml::encode($product->name); ?><br /><span class="each"><?php echo SnapFormat::currency($purchase->item_sales_price) ?> ea.<span></h3>
			<?php if(!$pastDeadline): ?>
				<div class="row">
					<div class="large-6 columns">
						<?php echo $product->getQuantityInput($data, $form, 'extras['.$data->id.']'); ?>
					</div>
					<div class="large-6 columns">
						<?php echo CHtml::submitButton('Update',array('class'=>'button tiny')); ?>
					</div>
				</div>
			<?php else: ?>
				<span class="quantity"><strong>Qty:</strong> <?php echo $data->quantity; ?></span>
			<?php endif; ?>
			<span class="price"><strong>Price:</strong> <?php echo CHtml::encode(SnapFormat::currency($data->total)); ?></span>
		</div>
	</div>
</div>