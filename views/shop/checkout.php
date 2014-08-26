<?php
$cs = Yii::app()->clientScript;
$baseUrl = Yii::app()->baseUrl;
$themeUrl = $baseUrl . Yii::app()->theme->baseUrl;
?>

<h1>Checkout</h1>


<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'checkout-form',
    'enableAjaxValidation' => false,
));
?>

    <div class="items row list-view">
        <?php foreach ($DeliveryDate->MergedBoxes as $Box):
            $UserBox = UserBox::findUserBox($DeliveryDate->id, $Box->size_id, $Customer->id);
            if (!$UserBox) continue;
            $quantity = $UserBox ? $UserBox->quantity : 0;
        ?>
        <div class="col-md-4">
            <div class="image">
                <?php echo SnapHtml::image($Box->BoxSize, 'image', array('w' => 70, 'h' => 70, 'zc' => 1)) ?>
            </div>
        </div>
        <div class="col-md-8 ">
            <h3><?php echo CHtml::encode($Box->BoxSize->box_size_name); ?> Box <br /><span class="each"><?php echo SnapFormat::currency($Box->box_price) ?> ea.<span></h3>
            <?php if (!$pastDeadline): ?>
                <div class="row">
                    <div class="col-md-6 ">
                        <?php echo CHtml::dropDownList('boxes[' . $Box->box_id . ']', $quantity, UserBox::$quantityOptions); ?>
                    </div>
                    <div class="col-md-6 ">
                        <?php echo CHtml::submitButton('Update', array('class' => 'button tiny')); ?>
                    </div>
                </div>
            <?php else: ?>
                <span class="quantity"><strong>Qty:</strong> <?php echo $quantity; ?></span>
            <?php endif; ?>
            <span class="price"><strong>Price:</strong> <?php echo $UserBox->total_price; ?> inc. Delivery </span>
        </div>
    <?php endforeach; ?>
    </div>

    <div class="row">
        <div class="col-md-9 products order">
            <h2>Cart <small>for delivery on <?php echo SnapFormat::date($DeliveryDate->date); ?></small></h2>
            <div id="checkout-cart" class="items list-view">
            <?php foreach($BoxoCart->userBoxes as $UserBox): ?>
                <?php echo $this->renderPartial('../userBox/_view',array(
                    'data' => $UserBox,
                )) ?>
            <?php endforeach; ?>

            <?php foreach($BoxoCart->products as $SP): ?>
                <?php echo $this->renderPartial('../orderItem/_view',array(
                    'data' => $SP,
                )) ?>
            <?php endforeach; ?>
            </div>

            <div class="inner">
                <div class="row">
                    <div class="col-xs-8">
                        <strong>Total:</strong>
                    </div>
                    <div class="price-col col-xs-4">
                        <strong><?php echo SnapFormat::currency($BoxoCart->total); ?></strong>
                    </div>
                </div>
            </div>
            
            <div class="dropDownPanel" id="repeatOrderDropdown">
                <div class="row">
                    <div class="col-md-4">
                        <?php echo BsHtml::dropDownListControlGroup('months_advance', null, array(1 => '1 Month', 3 => '3 Months', 6 => '6 Months'), array(
                            'label' => 'Order in advance for',
                            'prompt' => 'Don\'t order in advance'
                        )); ?>
                    </div>
                    <!--
                    <div class="col-md-3">
                        <?php echo BsHtml::dropDownListControlGroup('starting_from', 1, $DeliveryDate->getFutureDeliveryDates(), array(
                            'label' => 'Starting from',
                        )); ?>
                    </div>
                    -->
                    <div class="col-md-3">
                        <?php echo BsHtml::dropDownListControlGroup('every', 1, array('week' => 'week', 'fortnight' => 'fortnight'), array(
                            'label' => 'Every',
                            'prompt' => '- Select -',
                        )); ?>
                    </div>
                    <div class="col-md-3">
                        <?php echo BsHtml::dropDownListControlGroup('pay_now', 1, array('now' => 'Pay all now', 'later' => 'Pay for next delivery only'), array(
                            'label' => 'Pay now?',
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
            
            <?php echo CHtml::submitButton('Purchase', array('name' => 'purchase', 'class' => 'btn btn-primary', 'id' => 'btn-recurring')); ?>
        </div>
        
        <div class="col-md-3">
            <!--
            <script type="text/javascript">
                var curUrl = "<?php echo $this->createUrl('/shop/default/index'); ?>";
                var curUrlWithId = "<?php echo $this->createUrl('/shop/default/index', array('id' => $DeliveryDate->id)); ?>";
                var selectedDate =<?php echo $DeliveryDate ? "'$DeliveryDate->date'" : 'null' ?>;
                var availableDates =<?php echo json_encode(SnapUtil::makeArray($AllDeliveryDates)) ?>;
            </script>
            -->
            <div class="item-list">
                <h4>Your upcoming orders</h4>
                
                <?php if(!empty($futureOrders)): ?>
                <?php /*foreach ($DeliveryDates as $DD):
                    $total = $Customer->totalByDeliveryDate($DD->id);
                    if ($total == 0)
                        continue;
                    ?>
                    <div class="item">
                        <p><?php echo CHtml::link(SnapFormat::date($DD->date), array('extras/order', 'date' => $DD->id)) ?> 
                            <span class="right"><?php echo SnapFormat::currency($total) ?></span></p>
                    </div>
                <?php endforeach; */?>
                <p><strong><?php echo CHtml::link('View All', array('customer/orders'), array('class' => 'button small right')) ?></strong></p>
                <?php else: ?>
                <p class="text-muted">No upcoming orders.</p>
                <?php endif; ?>

            </div>
        </div>
        
    </div>

<?php $this->endWidget(); ?>