<?php
$cs = Yii::app()->clientScript;
$baseUrl = Yii::app()->baseUrl;
$themeUrl = $baseUrl . Yii::app()->theme->baseUrl;
$cs->registerCssFile($themeUrl . '/css/ui-lightness/jquery-ui.css');
$cs->registerCoreScript('jquery.ui');
$cs->registerScriptFile($themeUrl . '/js/ui.datepicker.min.js', CClientScript::POS_END);
$cs->registerScriptFile($themeUrl . '/js/chosen.jquery.min.js', CClientScript::POS_END);
$cs->registerScriptFile($themeUrl . '/js/order/_form.js', CClientScript::POS_END);

$cartEmpty = empty($BoxoCart->userBoxes) && empty($BoxoCart->products);

?>

<h1 class="sr-only">Shop</h1>

<?php if(!$BoxoCart->location_id): ?>
<?php
$form = $this->beginWidget('bootstrap.widgets.BsActiveForm', array(
    'id' => 'location-form',
    'enableAjaxValidation' => false,
    //'layout' => BsHtml::FORM_LAYOUT_INLINE
));
?>
<div class="alert alert-warning form-inline">
    <div class="row">
        <div class="col-md-5">
            <div class="form-group">
                <h3>Set Location</h3>
                <?php echo BsHtml::label('Set Location', 'BoxoCart_location_id', array('class'=>'sr-only')) ?>
                <?php echo BsHtml::dropDownList('BoxoCart[location_id]',$BoxoCart->location_id,Location::getDeliveryAndPickupList(),array(
                    'name' => 'BoxoCart[location_id]',
                    'prompt' => '- Select -',
                )); ?>
                <?php echo BsHtml::submitButton('Set Location'); ?>
                <p class="hint">
                    <small>This is required to determine which products are available.</small>
                </p>
            </div>
        </div>
        <div class="col-md-1 text-center">
            <p>&nbsp;</p>
            <h3> - Or - </h3>
        </div>
        <div class="col-md-4 col-md-offset-1">
            <h3>Login</h3>
            <p>If you already have an account <?php echo CHtml::link('Login here', array('/site/login')) ?></p>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>

<?php else: ?>

<?php echo $this->renderPartial('_ordering_on',array(
    'DeliveryDate' => $DeliveryDate,
    'Location' => $Location,
)) ?>

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
                        <span class="available-until"></span>
                        <?php
                        $form = $this->beginWidget('CActiveForm', array(
                            'id' => 'box-form-' . $Box->box_id,
                            'enableAjaxValidation' => false,
                        ));
                        ?>
                        <div class="image">
                            <?php echo SnapHtml::activeImage($Box->BoxSize, 'image', array('w' => 190, 'h' => 100, 'zc' => 1), $Box->BoxSize->box_size_name, true); ?>
                        </div>
                        <span class="price"><?php echo SnapFormat::currency($Box->box_price); ?></span>

                        <?php echo CHtml::numberField('Order[UserBox][' . $Box->box_id . ']', 1); ?>
                        <?php echo CHtml::submitButton('Add to cart', array('class' => 'btn btn-default btn-xs', 'name' => 'add_to_cart')); ?>
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
            'emptyText' => '<div class="col-md-12">No items found</div>',
            'emptyTagName' => 'div',
            'itemView' => '../supplierProduct/_view',
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
        
        <?php if(!$cartEmpty): ?>
        <div class="items list-view">
        <?php foreach($BoxoCart->userBoxes as $UserBox): ?>
            <?php echo $this->renderPartial('../userBox/_view_cart',array(
                'data' => $UserBox,
            )) ?>
        <?php endforeach; ?>
        
        <?php foreach($BoxoCart->products as $SP): ?>
           <?php echo $this->renderPartial('../orderItem/_view_cart',array(
               'data' => $SP,
               'form' => $form,
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
        <?php echo CHtml::submitButton('Update Cart', array('class' => 'btn btn-sm btn-default pull-left', 'name' => 'update_cart')); ?>
        <?php echo !$cartEmpty ? CHtml::link('Checkout',array('shop/checkout'), array('class'=>'btn btn-sm btn-success pull-right')) : '' ?>
        
        
        <?php else: ?>
        <p>Nothing in cart.</p>
        <?php endif; ?>

    </div>
<?php $this->endWidget(); ?>
</div>

<?php endif; ?>



