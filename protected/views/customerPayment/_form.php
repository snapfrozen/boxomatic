<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'customer-payment-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'payment_value'); ?>
		<?php echo $form->textField($model,'payment_value',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'payment_value'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'payment_type'); ?>
		<?php echo $form->textField($model,'payment_type',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'payment_type'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->