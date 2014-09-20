<?php $dds = $Location->getDeliveryDays() ?>
<div class="alert alert-info">
    This order is for delivery on <strong><?php echo SnapFormat::date($DeliveryDate->date, 'full')?></strong> at <strong><?php echo $Location->location_name ?></strong>
</div>
<?php /* if(count($dds) > 1): ?>
<div class="alert alert-warning">
    This location has a delivery run <strong><?php echo count($dds) ?></strong> times a week on 
    <?php $lastDay = array_pop($dds); ?>
    <?php echo implode(', ', $dds); ?> and <?php echo $lastDay ?>
</div>
<?php endif; */ ?>
