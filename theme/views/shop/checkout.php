<?php
$cs = Yii::app()->clientScript;
$baseUrl = Yii::app()->baseUrl;
$themeUrl = $baseUrl . Yii::app()->theme->baseUrl;

$Paypal = Yii::app()->getModule('payPal');
?>

<div class="row">
    <div class="col-md-8 col-md-offset-2 products order">
        <h1>Your Orders</h1>
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'checkout-form',
            'enableAjaxValidation' => false,
        ));
        ?>
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
        <?php $this->endWidget() ?>
        
        <div id="checkout-cart" class="items list-view panel-group">
        <?php
        /* @todo: get Order objects instead $BoxoCart->getOrders() */
        foreach($BoxoCart->getDeliveryDates(true) as $DD): ?>
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
                        
                        <?php foreach($BoxoCart->getUserBoxes('both') as $CustomerBox): ?>
                            <?php echo $this->renderPartial('../userBox/_view_checkout',array(
                                'data' => $CustomerBox,
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
        
        <div class="row">
            <div class="col-md-9">
                <h2>Payment</h2>
                <p class="alert alert-info">Your current balance is: <strong><?php echo SnapFormat::currency($Customer->balance) ?></strong></p>
                <form name="order" action="https://www.<?php echo $Paypal->env == '' ? '' : $Paypal->env.'.' ?>paypal.com/cgi-bin/webscr" method="post">
                <?php $minDays = SnapUtil::config('boxomatic/minimumAdvancePayment');?>
                <?php $daysToLastDate = $BoxoCart->getDaysToLastDate(); ?>
                    <fieldset>
                        <table class="table">
                            <tbody>
                                <?php if($BoxoCart->getNextTotal($minDays) == 0): ?>
                                <tr>
                                    <td>You have enough credit in your account.</td>
                                    <td><?php echo CHtml::link('Confirm order', array('shop/confirmOrder'),array(
                                        'class' => 'btn btn-success form-control'
                                    )); ?></td>
                                </tr>
                                <?php elseif($daysToLastDate > $minDays): ?>
                                <tr>
                                    <td>Pay up until x</td>
                                    <td>
                                        <?php echo CHtml::tag('button', array(
                                            'name' => 'amount',
                                            'type' => 'submit',
                                            'value' =>  $BoxoCart->getNextTotal($minDays),
                                            'class' => 'btn btn-primary form-control'),
                                        SnapFormat::currency($BoxoCart->getNextTotal($minDays))); ?>
                                    </td>
                                </tr>
                                <?php endif; ?>

                                <?php if($BoxoCart->getNextTotal($minDays * 2) !== 0): ?>
                                <tr>
                                    <td>Pay up until <strong><?php echo $BoxoCart->getOrderDate($minDays) ?></strong></td>
                                    <td>
                                        <?php echo CHtml::tag('button', array(
                                            'name' => 'amount',
                                            'type' => 'submit',
                                            'value' =>  $BoxoCart->getNextTotal($minDays * 2),
                                            'class' => 'btn btn-primary form-control'),
                                        SnapFormat::currency($BoxoCart->getNextTotal($minDays * 2))); ?>
                                    </td>
                                </tr>
                                <?php endif; ?>

                                <?php if($BoxoCart->getAllTotal() !== 0): ?>
                                <tr>
                                    <td>Pay for all your orders</td>
                                    <td>
                                        <?php echo CHtml::tag('button', array(
                                            'name' => 'amount',
                                            'type' => 'submit',
                                            'value' => $BoxoCart->getAllTotal(),
                                            'class' => 'btn btn-primary form-control'),
                                        'Pay ' . SnapFormat::currency($BoxoCart->getAllTotal()) . ' now'); ?>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td>Top up store credit</td>
                                    <td class="form-inline">
                                        <input type="text" name="amount" class="form-control"/>
                                        <input type="submit" class="btn btn-primary" value="Go"/>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <input type="hidden" name="cmd" value="_ext-enter" />

                        <input type="hidden" name="redirect_cmd" value="_xclick" />
                        <input type="hidden" name="return" value = "<?php echo $this->createAbsoluteUrl('customerPayment/paypalSuccess') ?>" />
                        <input type="hidden" name="cancel_return" value = "<?php echo $this->createAbsoluteUrl('customerPayment/paypalFailure') ?>" />
                        <input type="hidden" name="business" value="<?php echo Yii::app()->getModule('payPal')->account->username ?>" />
                        <input type="hidden" name="item_name" value="Box-O-Matic '<?php echo Yii::app()->name ?>' Credit" />
                        <input type="hidden" name="quantity" value="1" />

                        <input type="hidden" name="email" value="<?php echo $Customer->email ?>" />
                        <input type="hidden" name="first_name" value="<?php echo $Customer->first_name ?>" />
                        <input type="hidden" name="last_name" value="<?php echo $Customer->last_name ?>" />
                        <input type="hidden" name="address1" value ="<?php echo $Customer->user_address ?>" />
                        <input type="hidden" name="address2" value ="<?php echo $Customer->user_address2 ?>" />
                        <input type="hidden" name="city" value="" />
                        <input type="hidden" name="state" value="<?php echo $Customer->user_state ?>" />
                        <input type="hidden" name="zip" value="<?php echo $Customer->user_postcode ?>" />
                        <input type="hidden" name="custom" value="<?php echo $Customer->id ?>" />
                        <input type="hidden" name="currency_code" value="AUD" />

                    </fieldset>
                </form>  
            </div>
        </div>
        
    </div>
</div>