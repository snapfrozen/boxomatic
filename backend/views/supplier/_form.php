<div class="form">
<?php $form=$this->beginWidget('application.widgets.SnapActiveForm', array(
	'id'=>'supplier-form',
	'enableAjaxValidation'=>false,
	'layout' => BsHtml::FORM_LAYOUT_HORIZONTAL,
	'htmlOptions' => array('class'=>'row'),
)); ?>

	<div class="col-lg-9 clearfix">
		<?php echo $form->textFieldControlGroup($model,'name'); ?>
		<?php echo $form->textFieldControlGroup($model,'company_name'); ?>
		<?php //echo $form->textFieldControlGroup($model,'Ordering'); ?>
		<?php echo $form->textFieldControlGroup($model,'email'); ?>
		<?php echo $form->textFieldControlGroup($model,'website'); ?>
		<?php echo $form->textFieldControlGroup($model,'phone'); ?>
		<?php echo $form->textFieldControlGroup($model,'mobile'); ?>
		<?php echo $form->textFieldControlGroup($model,'address'); ?>
		<?php echo $form->textFieldControlGroup($model,'suburb'); ?>
		<?php echo $form->textFieldControlGroup($model,'postcode'); ?>
		<?php echo $form->dropDownListControlGroup($model,'state',SnapUtil::config('boxomatic/states')); ?>
		<?php echo $form->textFieldControlGroup($model,'ABN'); ?>
		<?php echo $form->textFieldControlGroup($model,'distance_kms'); ?>
		<?php echo $form->textFieldControlGroup($model,'bank_account_name'); ?>
		<?php echo $form->textFieldControlGroup($model,'bank_bsb'); ?>
		<?php echo $form->textFieldControlGroup($model,'bank_acc'); ?>
		<?php echo $form->textFieldControlGroup($model,'certification_status'); ?>
		<?php echo $form->textFieldControlGroup($model,'order_days'); ?>
		<?php echo $form->textAreaControlGroup($model,'produce'); ?>
		<?php echo $form->textAreaControlGroup($model,'notes'); ?>
		<?php echo $form->textFieldControlGroup($model,'payment_details'); ?>
		<?php echo $form->textFieldControlGroup($model,'lattitude'); ?>
		<?php echo $form->textFieldControlGroup($model,'longitude'); ?>
	</div>

	<?php echo $this->renderPartial('//layouts/_form_sidebar'); ?>

<?php $this->endWidget(); ?>
</div>