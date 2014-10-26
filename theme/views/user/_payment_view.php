<div class="row">
    <div class="col-sm-2">
        <?php echo SnapFormat::datetime($data->payment_date, 'short'); ?>
    </div>
    <div class="col-sm-6">
        <?php echo CHtml::encode($data->payment_note); ?>
    </div>
    <div class="col-sm-1">
        <?php echo CHtml::encode($data->payment_type); ?>
    </div>
    <div class="col-sm-1 text-right">
        <?php echo $data->payment_value > 0 ? SnapFormat::currency($data->payment_value) : ''; ?>
    </div>
    <div class="col-sm-1 text-right">
        <?php echo $data->payment_value < 0 ? SnapFormat::currency(abs($data->payment_value)) : ''; ?>
    </div>
    <div class="col-sm-1 text-right">
        <?php echo SnapFormat::currency($data->balance_at_date); ?>
    </div>
</div>