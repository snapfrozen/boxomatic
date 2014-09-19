<?php
$cs = Yii::app()->clientScript;
$baseUrl = Yii::app()->baseUrl;
$themeUrl = $baseUrl . Yii::app()->theme->baseUrl;
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'checkout-form',
    'enableAjaxValidation' => false,
));
?>
<div class="row">
    <div class="col-md-8 col-md-offset-2 products order">
        <h1>Your Orders</h1>
        
        <div class="dropDownPanel" id="repeatOrderDropdown">
            <div class="row">
                <div class="col-md-4">
                    <?php echo BsHtml::dropDownListControlGroup('months_advance', null, array(1 => '1 Month', 3 => '3 Months', 6 => '6 Months'), array(
                        'label' => 'Order in advance for',
                        'prompt' => 'Don\'t order in advance'
                    )); ?>
                </div>
                <div class="col-md-3">
                    <?php echo BsHtml::dropDownListControlGroup('every', 1, array('week' => 'week', 'fortnight' => 'fortnight'), array(
                        'label' => 'Every',
                        'prompt' => '- Select -',
                    )); ?>
                </div>
                <div class="col-md-2">
                    <div class="form-group no-label">
                        <?php echo CHtml::submitButton('Update order', array('name' => 'btn_recurring', 'class' => 'btn btn-default', 'id' => 'btn-recurring')); ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="checkout-cart" class="items list-view panel-group">
        <?php foreach($BoxoCart->getDeliveryDates(true) as $DD): ?>
            <?php $BoxoCart->delivery_date_id = $DD->id; ?>
            <div class="panel panel-default <?php echo $BoxoCart->currentOrderExists() ? '' : 'panel-success' ?> <?php echo $BoxoCart->currentOrderRemoved() ? '' : 'panel-danger' ?> <?php echo $DD->id == $DeliveryDate->id ? 'selected' : '' ?>">
                <a class="panel-heading" data-toggle="collapse" data-parent="#checkout-cart" href="#collapse-<?php echo $DD->id ?>">
                    <h3>
                        <?php echo SnapFormat::date($BoxoCart->DeliveryDate->date, 'full') ?>
                        <span class="pull-right"><?php echo $BoxoCart->getTotalLabel($DD->id) ?></span>
                    </h3>
                </a>
                <div id="collapse-<?php echo $DD->id ?>" class="panel-collapse collapse <?php echo $DD->id == $DeliveryDate->id ? 'in' : '' ?>">
                    <div class="panel-body">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <p>Delivery to: <strong><?php echo $BoxoCart->Location->location_name; ?></strong>
                            </div>
                            <div class="col-md-6">
                                <div class="buttons pull-right">
                                    <?php echo CHtml::link('Update this order', array('/shop/index','set-date'=>$DD->id),array('class'=>'btn btn-info')) ?>
                                    <?php echo CHtml::link('Delete this order', array('shop/removeOrder','id'=>$DD->id), array(
                                        'class'=>'btn btn-danger', 
                                        'confirm' => 'Are you sure you want to delete this order?'
                                    )); ?>
                                </div>
                            </div>
                        </div>
                        
                        <?php foreach($BoxoCart->getUserBoxes('both') as $UserBox): ?>
                            <?php echo $this->renderPartial('../userBox/_view_checkout',array(
                                'data' => $UserBox,
                                'BoxoCart' => $BoxoCart,
                                'ddId' => $DD->id,
                            )) ?>
                        <?php endforeach; ?>

                        <?php foreach($BoxoCart->getProducts('both') as $SP): ?>
                            <?php echo $this->renderPartial('../orderItem/_view_checkout',array(
                                'data' => $SP,
                                'BoxoCart' => $BoxoCart,
                                'ddId' => $DD->id,
                            )) ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
        
        <p>Your Balance: <strong><?php echo SnapFormat::currency($Customer->balance) ?></strong></p>
        
        <div class="btn-group">
            <?php $minDays = SnapUtil::config('boxomatic/minimumAdvancePayment');?>
            <?php $daysToLastDate = $BoxoCart->getDaysToLastDate(); ?>
            
            <?php if($BoxoCart->getNextTotal($minDays) == 0): ?>
                <?php echo CHtml::link('Confirm changes', array('shop/confirmOrder'),array('class' => 'btn btn-success')); ?>
            <?php elseif($daysToLastDate > $minDays): ?>
                <?php echo CHtml::submitButton('Pay ' . SnapFormat::currency($BoxoCart->getNextTotal($minDays)) . ' now', array('name' => 'purchase', 'class' => 'btn btn-primary', 'id' => 'btn-recurring')); ?>
            <?php endif; ?>
            
            <?php if($daysToLastDate > $minDays * 2): ?>
                <?php echo CHtml::submitButton('Pay ' . SnapFormat::currency($BoxoCart->getNextTotal($minDays * 2)) . ' now', array('name' => 'purchase', 'class' => 'btn btn-primary', 'id' => 'btn-recurring')); ?>
            <?php endif; ?>
            
            <?php echo CHtml::submitButton('Pay ' . SnapFormat::currency($BoxoCart->getAllTotal()) . ' now', array('name' => 'purchase', 'class' => 'btn btn-primary', 'id' => 'btn-recurring')); ?>
            
        </div>

    </div>
</div>

<?php $this->endWidget(); ?>