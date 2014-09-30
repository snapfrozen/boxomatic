<?php
$cs = Yii::app()->clientScript;
$baseUrl = Yii::app()->baseUrl;
$themeUrl = $baseUrl . Yii::app()->theme->baseUrl;

$Paypal = Yii::app()->getModule('payPal');
$minDays = SnapUtil::config('boxomatic/minimumAdvancePayment');
?>

<div class="row">
    <div class="col-md-8 col-md-offset-2 products order">
        <div class="page-header">
            <h1>Past Orders</h1>
        </div>

        <div id="checkout-cart" class="items list-view panel-group">
            <?php
            /* @todo: get Order objects instead $BoxoCart->getOrders() */
            foreach($Orders as $Order): 
                $DD = $Order->DeliveryDate;
                $Location = $Order->Location;
            ?>
            <div class="panel panel-default">
                <a class="panel-heading" data-toggle="collapse" data-parent="#checkout-cart" href="#collapse-<?php echo $DD->id ?>">
                    <h3>
                        <?php echo SnapFormat::date($DD->date, 'full') ?>
                        <span class="pull-right">
                            <?php echo SnapFormat::currency($Order->total) ?>
                            <span class="glyphicon"></span>
                        </span>
                    </h3>
                </a>
                <div id="collapse-<?php echo $DD->id ?>" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p>Delivery to: <strong><?php echo $Location->location_name; ?></strong>
                            </div>
                            <div class="col-md-6"></div>
                        </div>
                        
                        <?php foreach($Order->UserBoxes as $CustomerBox): ?>
                            <?php echo $this->renderPartial('../userBox/_view_checkout',array(
                                'data' => $CustomerBox,
                                'ddId' => $DD->id,
                            )) ?>
                        <?php endforeach; ?>

                        <?php foreach($Order->Extras as $SP): ?>
                            <?php echo $this->renderPartial('../orderItem/_view_checkout',array(
                                'data' => $SP,
                                'ddId' => $DD->id,
                            )) ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>