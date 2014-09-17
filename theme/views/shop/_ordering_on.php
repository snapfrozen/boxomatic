<div class="alert alert-info">
    This order is for delivery on <strong><?php echo CHtml::link(SnapFormat::date($DeliveryDate->date, 'long'),array('/shop/orders')) ?></strong> at <strong><?php echo $Location->location_name ?></strong>
    (<?php echo CHtml::link('Change Location', array('shop/changeLocation')); ?>)
</div>