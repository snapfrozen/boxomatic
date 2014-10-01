<div class="view col-sm-4">
    <div class="inner">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'extras-form-' . $data->id,
        'enableAjaxValidation' => false,
    ));
    ?>
        <h3><?php echo CHtml::encode($data->name); ?></h3>
        
        <span class="available-until">
            Available until: <?php echo SnapFormat::date($data->customer_available_to); ?>
        </span>
        
        <div class="image">
            <?php echo SnapHtml::activeImage($data, 'image', array('w' => 190, 'h' => 100, 'zc' => 1), $data->name, true) ?>
        </div>
        
        <?php if(!empty($data->description)): ?>
        <span class="description"><?php echo $data->description; ?></span>
        <?php endif; ?>
        
        <span class="price">
            <?php echo CHtml::encode(SnapFormat::currency($data->item_sales_price)); ?>
            (<?php echo $data->unit_label ?>)
        </span>

        <?php echo $data->getQuantityInput($form, null, 1); ?>
        <?php echo CHtml::submitButton('Add to cart', array('class' => 'btn btn-default btn-xs', 'name' => 'add_to_cart')); ?>
    <?php $this->endWidget(); ?>
    </div>
</div>
<?php //var_dump(($index+1) % 3); ?>
<?php if(($index+1) % 3 == 0) { echo '</div><div class="items row">'; } ?>