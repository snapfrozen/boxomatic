<?php 
$Box = $data->Box;
?>
<div class="view inner">
    <div class="row">
        <div class="col-xs-3">
            <?php echo CHtml::numberField('Order[UserBox][' . $Box->box_id . ']', $data->quantity); ?>
            <!-- <?php echo SnapHtml::activeImage($Box->BoxSize, 'image', array('w' => 70, 'h' => 70, 'zc' => 1),$Box->BoxSize->box_size_name) ?> -->
        </div>
        <div class="col-xs-5">
            <h3><?php echo CHtml::encode($Box->BoxSize->box_size_name); ?></h3>
            <span class="price item-price">
                <?php echo SnapFormat::currency($Box->box_price) ?> ea.
            </span>
        </div>
        <div class="col-xs-4 price-col">
            <span class="price sub-total"><?php echo CHtml::encode(SnapFormat::currency($data->total_price)); ?></span>
        </div>
    </div>
</div>