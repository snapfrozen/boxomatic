<div class="form">
    <?php
    $form = $this->beginWidget('application.widgets.SnapActiveForm', array(
        'id' => 'supplier-item-form',
        'enableAjaxValidation' => false,
        'layout' => BsHtml::FORM_LAYOUT_HORIZONTAL,
        'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => 'row')
    ));
    ?>

    <div class="col-lg-9 clearfix">

        <?php echo $form->textFieldControlGroup($model, 'name', array('size' => 45, 'maxlength' => 45)); ?>

        <?php echo $form->dropDownListControlGroup($model, 'supplier_id', CHtml::listData(Supplier::model()->findAll(), 'id', 'name'), array('class' => 'chosen')); ?>
        <?php echo $form->dropDownListControlGroup($model, 'packing_station_id', CHtml::listData(PackingStation::model()->findAll(), 'id', 'name'), array('class' => 'chosen')); ?>

        <?php echo $form->textFieldControlGroup($model, 'value', array('size' => 7, 'maxlength' => 7)); ?>
        <?php echo $form->dropDownListControlGroup($model, 'unit', $model->getUnitList()); ?>
        <?php echo $form->textFieldControlGroup($model, 'quantity_options', array('help' => 'A comma separated list of values. Leave blank to allow the user to define any quantity they like.')); ?>

        <?php echo $form->dropDownListControlGroup($model, 'available_from', $model->getMonthList()); ?>
        <?php echo $form->dropDownListControlGroup($model, 'available_to', $model->getMonthList()); ?>

        <?php echo $form->imageField($model, 'image'); ?>
        <?php echo $form->textAreaControlGroup($model, 'description'); ?>
        <?php echo $form->textFieldControlGroup($model, 'item_sales_price'); ?>
        
        <?php echo $form->dateFieldControlGroup($model, 'customer_available_from', array(), array('yearRange'=>date('Y').':2050')); ?>
        <?php echo $form->dateFieldControlGroup($model, 'customer_available_to', array(), array('yearRange'=>date('Y').':2050')); ?>

        <div class="row">
            <div class="col-md-offset-2 col-md-10">
                <h3>Categories</h3>
                <ul class="categories">
                    <?php echo Category::model()->getCategoryTreeForm(SnapUtil::config('boxomatic/supplier_product_root_id'), $model); ?>
                </ul>
            </div>
        </div>

    </div>
    <?php echo $this->renderPartial('//layouts/_form_sidebar'); ?>

<?php $this->endWidget(); ?>
</div>