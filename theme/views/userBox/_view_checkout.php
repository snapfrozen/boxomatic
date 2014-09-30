<?php 
$Box = $data->Box;
$BoxoCart = isset($BoxoCart) ? $BoxoCart : null;
?>
<div class="view inner <?php echo $BoxoCart && $BoxoCart->boxRemoved($data->box_id) ? 'text-danger' : ''?> <?php echo $BoxoCart && $BoxoCart->boxAdded($data->box_id) ? 'text-success' : ''?>">
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
    <?php if($BoxoCart && $BoxoCart->boxChanged($ddId, $data)): 
        $before = $BoxoCart->getBoxBefore($ddId, $data);
    ?>
    <div class="row text-danger before">
        <div class="col-xs-1 quantity">
            <?php echo $before->quantity ?>
        </div>
        <div class="col-xs-5">
            <h3><?php echo CHtml::encode($Box->BoxSize->box_size_label); ?></h3>
            <span class="price item-price">
                <?php echo SnapFormat::currency($Box->box_price) ?> ea.
            </span>
        </div>
        <div class="col-xs-4 price-col pull-right">
            <span class="price sub-total"><?php echo CHtml::encode(SnapFormat::currency($before->total_price)); ?></span>
        </div>
    </div>
    <?php endif; ?>
</div>