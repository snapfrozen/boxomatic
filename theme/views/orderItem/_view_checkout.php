<?php
/* @var $this OrderItemController */
/* @var $data OrderItem */
$product = $data->SupplierProduct;
//$extra = isset($updatedOrders[$data->id]) ? $updatedOrders[$data->id] : false;
?>
<div class="view inner">
    <div class="row">
        <div class="col-xs-1">
            <?php echo $data->quantity ?>
        </div>
        <?php /* if(isset($showImage)): ?>
        <div class="col-xs-3">
            <?php echo SnapHtml::activeImage($product, 'image', array('w' => 70, 'h' => 70, 'zc' => 1), $product->name, true) ?>
        </div>
        <?php endif; */ ?>
        <div class="col-xs-5">
            <h3><?php echo CHtml::encode($product->name); ?></h3>
            <span class="price item-price">
                <?php echo SnapFormat::currency($product->item_sales_price) ?>
                (<?php echo $product->unit_label ?>)
            </span>
            <span class="available-until">
                Available until: <?php echo SnapFormat::date($product->customer_available_to); ?>
            </span>
        </div>
        <div class="col-xs-4 price-col pull-right">
            <span class="price sub-total"><?php echo CHtml::encode(SnapFormat::currency($data->total)); ?></span>
        </div>
    </div>
</div>