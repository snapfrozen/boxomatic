<?php 
$Box = $data->Box;
?>
<div class="view inner <?php echo $BoxoCart->boxRemoved($data->box_id) ? 'text-danger' : ''?>"">
    <div class="row">
        <div class="col-xs-1 quantity">
            <?php echo $data->quantity ?>
        </div>
        <div class="col-xs-5">
            <h3><?php echo CHtml::encode($Box->BoxSize->box_size_label); ?></h3>
            <span class="price item-price">
                <?php echo SnapFormat::currency($Box->box_price) ?> ea.
            </span>
        </div>
        <div class="col-xs-4 price-col pull-right">
            <span class="price sub-total"><?php echo CHtml::encode(SnapFormat::currency($data->total_price)); ?></span>
        </div>
    </div>
</div>