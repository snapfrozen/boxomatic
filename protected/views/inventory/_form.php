<?php
/* @var $this InventoryController */
/* @var $model Inventory */
/* @var $form CActiveForm */
$cs=Yii::app()->clientScript;
$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/ui-lightness/jquery-ui.css');
$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/supplierproduct/_form.js',CClientScript::POS_END);
?>

<fieldset>
	<legend>Inventory</legend>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'inventory-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<div class="large-12 columns">
		<?php echo $form->errorSummary($model); ?>
		<?php echo CHtml::label('Supplier','supplier_id'); ?>
		<?php echo CHtml::dropDownList('supplier_id',CHtml::value($model,'supplierProduct.supplier_id'),Supplier::getDropdownListItems(),array('class'=>'chosen')); ?>
		<?php echo CHtml::hiddenField('update_url',$this->createUrl('supplier/getListItems')); ?>

		<?php echo $form->labelEx($model,'supplier_product_id'); ?>
		<?php if($model->supplierProduct): ?>
			<?php echo $form->dropDownList($model,'supplier_product_id',SupplierProduct::getDropdownListItems($model->supplierProduct->supplier_id),array('class'=>'chosen')); ?>
		<?php else: ?>
			<?php echo $form->dropDownList($model,'supplier_product_id',array(),array('class'=>'chosen')); ?>
		<?php endif; ?>
		<?php echo $form->error($model,'supplier_product_id'); ?>
	</div>

	<div class="large-6 columns">
		<?php echo $form->labelEx($model,'quantity'); ?>
		<?php echo $form->textField($model,'quantity',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'quantity'); ?>
	</div>
	
	<div class="large-12 columns">
		<?php echo $form->labelEx($model,'notes'); ?>
		<?php echo $form->textArea($model,'notes'); ?>
		<?php echo $form->error($model,'notes'); ?>
	</div>

	<div class="large-12 columns buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array('class'=>'button')); ?>
	</div>

<?php $this->endWidget(); ?>

</fieldset>