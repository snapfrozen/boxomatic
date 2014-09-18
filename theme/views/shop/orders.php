<?php
$cs = Yii::app()->clientScript;
$baseUrl = Yii::app()->baseUrl;
$themeUrl = $baseUrl . Yii::app()->theme->baseUrl;
?>

<?php echo $this->renderPartial('_ordering_on',array(
    'DeliveryDate' => $DeliveryDate,
    'Location' => $BoxoCart->Location,
)) ?>

<h1>Your Orders</h1>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'checkout-form',
    'enableAjaxValidation' => false,
));
?>

<div class="row">
    <div class="col-md-6">
        <h3>Order date</h3>
        <ul class="nav nav-pills nav-stacked with-button">
            <?php foreach($BoxoCart->getDeliveryDates() as $DD): ?>
            <li <?php echo $DD->id == $DeliveryDate->id ? 'class="active"' : '' ?>>
                <?php echo CHtml::link(SnapFormat::date($DD->date) . '<br /><small>Total: ' . SnapFormat::currency($BoxoCart->getTotal($DD->id)) . '</small>', array('shop/checkout','set-date'=>$DD->id)); ?>
                <?php echo CHtml::link('<span class="glyphicon glyphicon-remove"></span>', array('shop/removeOrder','id'=>$DD->id),array('class'=>'remove', 'title'=>'Remove this order' )); ?>
            </li>
            <?php endforeach; ?>
        </ul>
    </div> 
    
            
    <div class="col-md-3">
        <!--
        <script type="text/javascript">
            var curUrl = "<?php echo $this->createUrl('/shop/default/index'); ?>";
            var curUrlWithId = "<?php echo $this->createUrl('/shop/default/index', array('id' => $DeliveryDate->id)); ?>";
            var selectedDate =<?php echo $DeliveryDate ? "'$DeliveryDate->date'" : 'null' ?>;
            var availableDates =<?php //echo json_encode(SnapUtil::makeArray($AllDeliveryDates)) ?>;
        </script>
        -->
        <div class="item-list">
            <h3>Your upcoming orders</h3>

            <ul class="nav nav-pills nav-stacked">
                <?php foreach($Customer->getFutureOrders(365) as $Order): ?>
                <li>
                    <?php echo CHtml::link(SnapFormat::date($Order->DeliveryDate->date) . '<br /><small>Total: ' . SnapFormat::currency($Order->total) . '</small>', array('customer/viewOrder','id'=>$Order->id)); ?>
                </li>
                <?php endforeach; ?>

                <li><?php echo CHtml::link('View All', array('customer/orders'), array('class' => 'button small right')) ?></li>
            </ul>
        </div>
    </div>
</div>

<?php $this->endWidget(); ?>