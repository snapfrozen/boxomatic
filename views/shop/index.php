<?php
$cs = Yii::app()->clientScript;
$baseUrl = Yii::app()->baseUrl;
$themeUrl = $baseUrl . Yii::app()->theme->baseUrl;
$cs->registerCssFile($themeUrl . '/css/ui-lightness/jquery-ui.css');
$cs->registerCoreScript('jquery.ui');
$cs->registerScriptFile($themeUrl . '/js/ui.datepicker.min.js', CClientScript::POS_END);
$cs->registerScriptFile($themeUrl . '/js/chosen.jquery.min.js', CClientScript::POS_END);
$cs->registerScriptFile($themeUrl . '/js/order/_form.js', CClientScript::POS_END);
?>

<h1>Shop</h1>

<?php //if(!$BoxoCart->location_id): ?>
<?php
$form = $this->beginWidget('bootstrap.widgets.BsActiveForm', array(
    'id' => 'location-form',
    'enableAjaxValidation' => false,
    //'layout' => BsHtml::FORM_LAYOUT_INLINE
));
?>
<div class="alert alert-warning form-inline">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?php echo BsHtml::label('Location', 'BoxoCart_location_id') ?><br />
                <?php echo BsHtml::dropDownList('BoxoCart[location_id]',$BoxoCart->location_id,Location::getDeliveryAndPickupList(),array(
                    'name' => 'BoxoCart[location_id]',
                    'prompt' => ' - Select - ',
                )); ?>
                <?php echo BsHtml::submitButton('Set Location'); ?>
                <p class="hint">
                    Please select your location<br />
                    <small>This is required to make a purchase</small>
                </p>
                
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<?php //endif; ?>

<?php if($Customer): ?>
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'recurring-form',
        'enableAjaxValidation' => false,
    ));
    ?>
    <div class="col-md-4 location">
        <h4>Location:</h4>
        <?php //echo CHtml::ActiveDropDownList($CustDeliveryDate,'location_id',Location::getDeliveryAndPickupList());  ?>
        <?php echo CHtml::ActiveDropDownList($CustDeliveryDate, 'delivery_location_key', $Customer->getDeliveryLocations()); ?>
    </div>

    <div class="col-md-4 end">
        <script type="text/javascript">
            var curUrl = "<?php echo $this->createUrl('/shop/default/index'); ?>";
            var curUrlWithId = "<?php echo $this->createUrl('/shop/default/index', array('id' => $DeliveryDate->id)); ?>";
            var selectedDate =<?php echo $DeliveryDate ? "'$DeliveryDate->date'" : 'null' ?>;
            var availableDates =<?php echo json_encode(SnapUtil::makeArray($AllDeliveryDates)) ?>;
        </script>
        <div class="dropDown">
            <h4 data-dropdown="calendarDropdown">Delivery Date: <strong><?php echo $DeliveryDate ? SnapFormat::date($DeliveryDate->date) : 'None Selected'; ?></strong></h4>
            <div class="dropDownPanel" id="calendarDropdown">

                <div class="delivery-date-picker"></div>
                <div class="item-list">
                    <h5>Your upcoming orders</h5>
                    <?php foreach ($DeliveryDates as $DD):
                        $total = $Customer->totalByDeliveryDate($DD->id);
                        if ($total == 0)
                            continue;
                        ?>
                        <div class="item">
                            <p><?php echo CHtml::link(SnapFormat::date($DD->date), array('extras/order', 'date' => $DD->id)) ?> 
                                <span class="right"><?php echo SnapFormat::currency($total) ?></span></p>
                        </div>
                    <?php endforeach; ?>
                    <p><strong><?php echo CHtml::link('View All', array('customer/orders'), array('class' => 'button small right')) ?></strong></p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4  end">
        <div class="dropDown">
            <h4 data-dropdown="repeatOrderDropdown">Repeat this order</h4>
            <div class="dropDownPanel" id="repeatOrderDropdown">
                <p>This will reset your orders for the selected amount of time to 
                    the setting chosen here. Press the "Clear all orders" button to start over. </p>
                <div class="row">
                    <div class="col-md-4 ">
                        <?php echo CHtml::label('Order in advance for', 'months_advance'); ?>
                        <?php echo CHtml::dropDownList('months_advance', 1, array(1 => '1 Month', 3 => '3 Months', 6 => '6 Months')); ?>
                    </div>
                    <div class="col-md-4 ">
                        <?php echo CHtml::label('Starting from', 'starting_from'); ?>
                        <?php echo CHtml::dropDownList('starting_from', 1, $DeliveryDate->getFutureDeliveryDates()); ?>

                    </div>
                    <div class="col-md-4 ">
                        <?php echo CHtml::label('Every', 'every'); ?>
                        <?php echo CHtml::dropDownList('every', 1, array('week' => 'week', 'fortnight' => 'fortnight')); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 ">
                        <?php echo CHtml::submitButton('Set recurring order', array('name' => 'btn_recurring', 'class' => 'button small tiny right')); ?>
                        <?php echo CHtml::submitButton('Clear all orders', array('name' => 'btn_clear_orders', 'class' => 'button small tiny right')); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->endWidget(); ?>
<?php endif; ?>

<div class="row">
    <div class="col-md-2">
        <h2>Categories</h2>
        <ul class="categories">
            <li class="<?php echo ($curCat == Category::boxCategory ? 'selected' : '') ?>"><?php echo CHtml::link('Boxes', array('/shop/index', 'date' => $DeliveryDate->id, 'cat' => Category::boxCategory)) ?></li>
            <?php echo Category::model()->getCategoryTree(SnapUtil::config('boxomatic/supplier_product_root_id'), array('/shop/index', 'date' => $DeliveryDate->id), $curCat); ?>
            <li class="<?php echo ($curCat == Category::uncategorisedCategory ? 'selected' : '') ?>"><?php echo CHtml::link('Uncategorised', array('/shop/index', 'date' => $DeliveryDate->id, 'cat' => Category::uncategorisedCategory)) ?></li>
        </ul>
    </div>
    <div class="col-md-7 products">
    <?php if ($curCat == Category::boxCategory): ?>
        <h2>Boxes</h2>
        <p>You may select items you do not wish to have in your box <?php echo CHtml::link('here', array('user/dontWant', 'id' => Yii::app()->user->id)) ?>.</p>
        <div class="list-view items">
            <div class="row">
            <?php foreach ($DeliveryDate->MergedBoxes as $index=>$Box): ?>
                <div class="col-md-4 view">
                    <div class="inner">
                        <h3><?php echo $Box->BoxSize->box_size_name; ?> Box</h3>
                        <?php
                        $form = $this->beginWidget('CActiveForm', array(
                            'id' => 'box-form-' . $Box->box_id,
                            'enableAjaxValidation' => false,
                        ));
                        ?>
                        <div class="image">
                            <?php echo SnapHtml::activeImage($Box->BoxSize, 'image', array('w' => 70, 'h' => 70, 'zc' => 1), $Box->BoxSize->box_size_name, true); ?>
                        </div>
                        <span class="price"><?php echo SnapFormat::currency($Box->box_price); ?></span>

                        <?php echo CHtml::dropDownList('Order[UserBox][' . $Box->box_id . ']', 1, UserBox::$quantityOptions); ?>
                        <?php echo CHtml::submitButton('Add', array('class' => 'btn btn-default btn-sm', 'name' => 'add_to_cart')); ?>
                        <?php $this->endWidget(); ?>
                    </div>
                </div>
                <?php if(($index+1) % 3 == 0) { echo '</div><div class="row">'; } ?>
            <?php endforeach; ?>
            </div>
        </div>

    <?php else: ?>

        <?php if ($Category): ?>
            <h2><?php echo $Category->name ?></h2>
            <?php if (!empty($Category->description)): ?>
                <p><?php echo $Category->description ?></p>
            <?php endif; ?>
        <?php elseif ($curCat == Category::uncategorisedCategory): ?>
            <h2>Uncategorised</h2>
        <?php endif; ?>

        <?php
        $this->widget('zii.widgets.CListView', array(
            'dataProvider' => $dpProducts,
            'summaryText' => '',
            'itemsCssClass' => 'items row',
            'emptyText' => 'No items found',
            'itemView' => '../supplierProduct/_view',
            'viewData' => array('updatedExtras' => $updatedExtras),
        ));
        ?>

    <?php endif; ?>
    </div>

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'extras-form',
        'enableAjaxValidation' => false,
    ));
    ?>
    <div class="col-md-3 products order">
        <h2>Cart</h2>
        
        <div class="items list-view">
        <?php foreach($BoxoCart->userBoxes as $UserBox): ?>
            <?php echo $this->renderPartial('../userBox/_view',array(
                'data' => $UserBox,
            )) ?>
        <?php endforeach; ?>
        </div>
        
        <?php
            $this->widget('zii.widgets.CListView', array(
                'dataProvider' => $orderedExtras,
                'summaryText' => '',
                'itemsCssClass' => 'items',
                'emptyText' => '',
                'itemView' => '../orderItem/_view',
                'viewData' => array('form' => $form, 'updatedOrders' => $updatedOrders, 'pastDeadline' => $pastDeadline),
            ));
        ?>
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
        <?php echo CHtml::submitButton('Update', array('class' => 'btn btn-sm btn-default', 'name' => 'update_cart')); ?>
        
    <?php if ($Customer): ?>
            <h2>Your order (<?php echo SnapFormat::currency($Customer->totalByDeliveryDate($DeliveryDate->id)) ?>)</h2>

            <div class="items row list-view">
            <?php foreach ($DeliveryDate->MergedBoxes as $Box):
                $UserBox = UserBox::findUserBox($DeliveryDate->id, $Box->size_id, $Customer->id);
                if (!$UserBox) continue;
                $quantity = $UserBox ? $UserBox->quantity : 0;
            ?>
                <div class="view col-md-12">
                    <div class="row">
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
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
    <?php endif; ?>
    </div>
<?php $this->endWidget(); ?>
</div>

