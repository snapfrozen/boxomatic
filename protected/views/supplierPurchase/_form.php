<?php
$cs=Yii::app()->clientScript;
$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/ui-lightness/jquery-ui.css');
$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/supplierpurchase/_form.js',CClientScript::POS_END);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'supplier-purchase-form',
	'enableAjaxValidation'=>false,
)); ?>
	
<fieldset>
	<legend>Supplier Purchase Form</legend>

	<?php if($form->errorSummary($model)): ?>

		<div data-alert class="alert-box alert">
		 <?php echo $form->errorSummary($model); ?>
		 <a href="#" class="close">&times;</a>
		</div>

	<?php endif; ?>
	
		<?php echo CHtml::label('Supplier','supplier_id'); ?>
		<?php echo CHtml::dropDownList('supplier_id',CHtml::value($model,'supplierProduct.supplier_id'),Supplier::getDropdownListItems(),array('class'=>'chosen')); ?>
		<?php echo CHtml::hiddenField('update_url',$this->createUrl('supplier/getListItems')); ?>

		<?php echo $form->labelEx($model,'supplier_product_id'); ?>
		<?php if($model->supplierProduct): ?>
			<?php echo $form->dropDownList($model,'supplier_product_id',SupplierProduct::getDropdownListItems($model->supplierProduct->id),array('class'=>'chosen')); ?>
		<?php else: ?>
			<?php echo $form->dropDownList($model,'supplier_product_id',array(),array('class'=>'chosen')); ?>
		<?php endif; ?>
		<?php echo $form->error($model,'supplier_product_id'); ?>

		<h4 class="toggle"><span>[+]</span> Add new item</h4>
		<div class="toggleSectionContent hide">
			<?php echo $this->renderPartial('../supplierProduct/_form', array('model'=>new SupplierProduct, 'hideSupplier'=>true)); ?>
		</div>

		<?php echo $form->labelEx($model,'propsed_quantity'); ?>
		<?php echo $form->textField($model,'propsed_quantity',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'propsed_quantity'); ?>

		<?php echo $form->labelEx($model,'propsed_price'); ?>
		<?php echo $form->textField($model,'propsed_price',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'propsed_price'); ?>

		<?php echo $form->labelEx($model,'proposed_delivery_date'); ?>
		<?php 
			$this->widget('zii.widgets.jui.CJuiDatePicker',array(
				'attribute'=>'proposed_delivery_date',
				'model'=>$model,
				'options' => array(
					'dateFormat'=>'yy-mm-dd',
				)
			));	
		?>
		<?php echo $form->error($model,'proposed_delivery_date'); ?>

		<?php echo $form->labelEx($model,'order_notes'); ?>
		<?php echo $form->textArea($model,'order_notes',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'order_notes'); ?>

		<?php echo $form->labelEx($model,'delivered_quantity'); ?>
		<?php echo $form->textField($model,'delivered_quantity',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'delivered_quantity'); ?>

		<?php echo $form->labelEx($model,'final_price'); ?>
		<?php echo $form->textField($model,'final_price',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'final_price'); ?>

		<?php echo $form->labelEx($model,'delivery_notes'); ?>
		<?php echo $form->textArea($model,'delivery_notes',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'delivery_notes'); ?>

		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class'=>'button')); ?>
		<?php echo CHtml::submitButton($model->inventory ? 'Save and update inventory' : 'Save and add to inventory', array('class'=>'button','name'=>'updateInventory')); ?>

<?php $this->endWidget(); ?>