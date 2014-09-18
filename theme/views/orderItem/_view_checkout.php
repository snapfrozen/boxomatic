<?php
/* @var $this OrderItemController */
/* @var $data OrderItem */
$product = $data->SupplierProduct;
//$extra = isset($updatedOrders[$data->id]) ? $updatedOrders[$data->id] : false;
?>
<div class="view inner <?php echo $BoxoCart->productRemoved($product->id) ? 'text-danger' : ''?> <?php echo $BoxoCart->productAdded($product->id) ? 'text-success' : ''?>">
    <div class="row">
        <div class="col-xs-1 quantity">
            <?php echo (float) $data->quantity ?>
        </div>
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
    
    <?php if($BoxoCart->productChanged($ddId, $data)): 
        $before = $BoxoCart->getProductBefore($ddId, $data);
    ?>
    <div class="row text-danger before">
        <div class="col-xs-1 quantity">
            <?php echo (float) $before->quantity ?>
        </div>
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
            <span class="price sub-total"><?php echo CHtml::encode(SnapFormat::currency($before->total)); ?></span>
        </div>
    </div>
    <?php endif; ?>
    
</div>
