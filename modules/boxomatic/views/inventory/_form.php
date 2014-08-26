<?php
/* @var $this InventoryController */
/* @var $model Inventory */
/* @var $form CActiveForm */
$cs = Yii::app()->clientScript;
$baseUrl = $this->createFrontendUrl('/') . '/themes/boxomatic/admin';
$cs->registerCssFile($baseUrl . '/css/ui-lightness/jquery-ui.css');
$cs->registerScriptFile($baseUrl . '/js/supplierproduct/_form.js', CClientScript::POS_END);
$cs->registerCssFile($baseUrl . '/css/chosen.css');
$cs->registerCoreScript('jquery.ui');
$cs->registerScriptFile($baseUrl . '/js/chosen.jquery.min.js', CClientScript::POS_END);

$form = $this->beginWidget('application.widgets.SnapActiveForm', array(
    'id' => 'inventory-form',
    'layout' => BsHtml::FORM_LAYOUT_HORIZONTAL,
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'row'),
));
?>

<div class="col-lg-9 clearfix">
    <?php //echo $form->errorSummary($model);  ?>

    <div class="form-group">
        <?php echo BsHtml::label('Supplier', 'supplier_id', array('class' => 'col-lg-2 control-label')) ?>
        <div class="col-lg-10">
            <?php echo BsHtml::dropDownList('supplier_id', CHtml::value($model, 'supplierProduct.supplier_id'), Supplier::getDropdownListItems(), array('class' => 'chosen', 'prompt' => '- Select -')); ?>
            <?php echo CHtml::hiddenField('update_url', $this->createUrl('supplier/getListItems')); ?>
        </div>
    </div>

    <?php
    echo $form->dropDownListControlGroup($model, 'supplier_product_id', $model->supplierProduct ? SupplierProduct::getDropdownListItems($model->supplierProduct->supplier_id) : array(), array(
        'class' => 'chosen'
    ));
    ?>
    <?php echo $form->textFieldControlGroup($model, 'quantity', array('size' => 7, 'maxlength' => 7)); ?>
    <?php echo $form->textAreaControlGroup($model, 'notes'); ?>
</div>

<?php echo $this->renderPartial('//layouts/_form_sidebar'); ?>

<?php $this->endWidget(); ?>