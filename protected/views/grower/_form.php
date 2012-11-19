<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'grower-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	
	<div class="row">
		<?php echo $form->labelEx($model,'grower_name'); ?>
		<?php echo $form->textField($model,'grower_name'); ?>
		<?php echo $form->error($model,'grower_name'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'company_name'); ?>
		<?php echo $form->textField($model,'company_name'); ?>
		<?php echo $form->error($model,'company_name'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'Ordering'); ?>
		<?php echo $form->textField($model,'Ordering'); ?>
		<?php echo $form->error($model,'Ordering'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'grower_email'); ?>
		<?php echo $form->textField($model,'grower_email'); ?>
		<?php echo $form->error($model,'grower_email'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'grower_website'); ?>
		<?php echo $form->textField($model,'grower_website'); ?>
		<?php echo $form->error($model,'grower_website'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'grower_phone'); ?>
		<?php echo $form->textField($model,'grower_phone'); ?>
		<?php echo $form->error($model,'grower_phone'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'grower_mobile'); ?>
		<?php echo $form->textField($model,'grower_mobile'); ?>
		<?php echo $form->error($model,'grower_mobile'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'grower_address'); ?>
		<?php echo $form->textField($model,'grower_address'); ?>
		<?php echo $form->error($model,'grower_address'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'grower_address2'); ?>
		<?php echo $form->textField($model,'grower_address2'); ?>
		<?php echo $form->error($model,'grower_address2'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'grower_postcode'); ?>
		<?php echo $form->textField($model,'grower_postcode'); ?>
		<?php echo $form->error($model,'grower_postcode'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'grower_suburb'); ?>
		<?php echo $form->textField($model,'grower_suburb'); ?>
		<?php echo $form->error($model,'grower_suburb'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'grower_state'); ?>
		<?php echo $form->textField($model,'grower_state'); ?>
		<?php echo $form->error($model,'grower_state'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ABN'); ?>
		<?php echo $form->textField($model,'ABN'); ?>
		<?php echo $form->error($model,'ABN'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'grower_state'); ?>
		<?php echo $form->textField($model,'grower_state'); ?>
		<?php echo $form->error($model,'grower_state'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'grower_state'); ?>
		<?php echo $form->textField($model,'grower_state'); ?>
		<?php echo $form->error($model,'grower_state'); ?>
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
	
	<div class="row">
		<?php echo $form->labelEx($model,'lattitude'); ?>
		<?php echo $form->textArea($model,'lattitude'); ?>
		<?php echo $form->error($model,'lattitude'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'longitude'); ?>
		<?php echo $form->textArea($model,'longitude'); ?>
		<?php echo $form->error($model,'longitude'); ?>
	</div>
	

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->