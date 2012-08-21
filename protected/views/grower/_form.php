<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'grower-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'grower_website'); ?>
		<?php echo $form->textField($model,'grower_website',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'grower_website'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'grower_distance_kms'); ?>
		<?php echo $form->textField($model,'grower_distance_kms',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'grower_distance_kms'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'grower_bank_account_name'); ?>
		<?php echo $form->textField($model,'grower_bank_account_name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'grower_bank_account_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'grower_bank_bsb'); ?>
		<?php echo $form->textField($model,'grower_bank_bsb',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'grower_bank_bsb'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'grower_bank_acc'); ?>
		<?php echo $form->textField($model,'grower_bank_acc',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'grower_bank_acc'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'grower_certification_status'); ?>
		<?php echo $form->textField($model,'grower_certification_status',array('size'=>60,'maxlength'=>150)); ?>
		<?php echo $form->error($model,'grower_certification_status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'grower_order_days'); ?>
		<?php echo $form->textField($model,'grower_order_days',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'grower_order_days'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'grower_produce'); ?>
		<?php echo $form->textArea($model,'grower_produce',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'grower_produce'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'grower_notes'); ?>
		<?php echo $form->textArea($model,'grower_notes',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'grower_notes'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'grower_payment_details'); ?>
		<?php echo $form->textArea($model,'grower_payment_details',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'grower_payment_details'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->