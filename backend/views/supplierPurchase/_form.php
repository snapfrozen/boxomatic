<?php
$baseUrl = $this->createFrontendUrl('/').'/themes/boxomatic/admin';
$cs=Yii::app()->clientScript;
$cs->registerCssFile($baseUrl . '/css/ui-lightness/jquery-ui.css');
$cs->registerScriptFile($baseUrl . '/js/supplierpurchase/_form.js',CClientScript::POS_END);
?>
<div class="form">
<?php $form=$this->beginWidget('application.widgets.SnapActiveForm', array(
	'id'=>'supplier-purchase-form',
	'enableAjaxValidation'=>false,
	'layout' => BsHtml::FORM_LAYOUT_HORIZONTAL,
	'htmlOptions' => array('class'=>'row')
)); ?>
	<div class="col-lg-9 clearfix">
		<?php echo BsHtml::dropDownListControlGroup('supplier_id', CHtml::value($model,'supplierProduct.supplier_id'), Supplier::getDropdownListItems(), array(
			'formLayout'=>$form->layout,
			'label'=>'Supplier',
			//'class'=>'chosen',
		)) ?>
		<?php echo CHtml::hiddenField('update_url',$this->createUrl('supplier/getListItems')); ?>
		<?php echo $form->dropDownListControlGroup($model,'supplier_product_id',SupplierProduct::getDropdownListItems(CHtml::value($model,'supplierProduct.supplier_id')),array(
			'class'=>'chosen',
			'prompt'=>'- Select -',
		)); ?>
		
		<div class="row">
			<div class="col-md-offset-2 col-md-10">
				<p><a href="javascript:void(0)" data-target="#sp-form" data-toggle="collapse">Create new product</a></p>
			</div>
		</div>
		<div id="sp-form" class="collapse">
			<fieldset>
				<legend>New Product</legend>
				<?php echo $this->renderPartial('../supplierProduct/_inner_form', array('model'=>new SupplierProduct, 'hideSupplier'=>true, 'form'=>$form)); ?>
			</fieldset>
		</div>

		<fieldset>
			<legend>Order Information</legend>
		
			<?php echo $form->textFieldControlGroup($model,'propsed_quantity',array('size'=>7,'maxlength'=>7)); ?>
			<?php echo $form->textFieldControlGroup($model,'propsed_price',array('size'=>7,'maxlength'=>7)); ?>
			<?php echo $form->dateFieldControlGroup($model,'proposed_delivery_date'); ?>
			<?php echo $form->textAreaControlGroup($model, 'order_notes'); ?>
		</fieldset>
			
		<fieldset>
			<legend>Delivery Information</legend>

			<?php echo $form->textFieldControlGroup($model,'delivered_quantity',array('size'=>7,'maxlength'=>7)); ?>
			<?php echo $form->textFieldControlGroup($model,'final_price',array('size'=>7,'maxlength'=>7)); ?>
			<?php echo $form->dateFieldControlGroup($model,'delivery_date',array(
				'help'=>'<strong>Delivery Date</strong> must be filled in if the item to be added to inventory.'
			)); ?>
			<?php echo $form->textAreaControlGroup($model, 'delivery_notes'); ?>
		</fieldset>
		
		<fieldset>
			<legend>Sales</legend>
			<?php if(!empty($model->final_price)): ?>
			<div class="row">
				<div class="col-md-offset-2 col-md-10">
					<p>Wholesale Item Price: <strong><?php echo SnapFormat::currency($model->wholesale_price) ?></strong></p>		
				</div>
			</div>
			<?php endif; ?>

			<?php echo $form->textFieldControlGroup($model,'item_sales_price',array('rows'=>6, 'cols'=>50,
				'help'=>'Leave blank to automatically calculate at ' . (SupplierPurchase::defaultItemPriceMultiplier*100) . '%',
			)); ?>
		</fieldset>
		
	</div>
	<?php echo $this->renderPartial('//layouts/_form_sidebar'); ?>
	
<?php $this->endWidget(); ?>
</div>