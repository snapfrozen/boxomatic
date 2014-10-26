<?php BsHtml::$formLayoutHorizontalLabelClass = ''; ?>
<?php $form->layout = BsHtml::FORM_LAYOUT_VERTICAL; ?>
<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <?php echo $form->textFieldControlGroup($model, 'name', array('size' => 45, 'maxlength' => 45)); ?>
            <?php echo $form->dropDownListControlGroup($model, 'packing_station_id', CHtml::listData(PackingStation::model()->findAll(), 'id', 'name'), array('class' => 'chosen')); ?>

            <?php echo $form->textFieldControlGroup($model, 'value', array('size' => 7, 'maxlength' => 7)); ?>
            <?php echo $form->dropDownListControlGroup($model, 'unit', $model->getUnitList()); ?>
            
            <?php echo $form->imageField($model, 'image'); ?>
            <?php echo $form->textAreaControlGroup($model, 'description'); ?>
        </div>
        <div class="col-md-6">
            <?php echo $form->dropDownListControlGroup($model, 'available_from', $model->getMonthList()); ?>
            <?php echo $form->dropDownListControlGroup($model, 'available_to', $model->getMonthList()); ?>
            <?php echo $form->dateFieldControlGroup($model, 'customer_available_from', array(), array('yearRange'=>date('Y').':2050')); ?>
            <?php echo $form->dateFieldControlGroup($model, 'customer_available_to', array(), array('yearRange'=>date('Y').':2050')); ?>
            <?php echo $form->textFieldControlGroup($model, 'item_sales_price'); ?>
            <?php echo $form->checkBoxControlGroup($model, 'available_in_shop'); ?>
            <h3>Categories</h3>
            <ul class="categories">
                <?php echo Category::model()->getCategoryTreeForm(SnapUtil::config('boxomatic/supplier_product_root_id'), $model); ?>
            </ul>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button name="new_product" type="submit" class="btn btn-primary">Add Product</button>
</div>
<?php $form->layout = BsHtml::FORM_LAYOUT_HORIZONTAL; ?>
<?php BsHtml::$formLayoutHorizontalLabelClass = 'control-label col-lg-2'; ?>