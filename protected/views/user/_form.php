<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'user_email'); ?>
		<?php echo $form->textField($model,'user_email',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'user_email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_name'); ?>
		<?php echo $form->textField($model,'user_name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'user_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_phone'); ?>
		<?php echo $form->textField($model,'user_phone',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'user_phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_mobile'); ?>
		<?php echo $form->textField($model,'user_mobile',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'user_mobile'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_address'); ?>
		<?php echo $form->textField($model,'user_address',array('size'=>60,'maxlength'=>150)); ?>
		<?php echo $form->error($model,'user_address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_address2'); ?>
		<?php echo $form->textField($model,'user_address2',array('size'=>60,'maxlength'=>150)); ?>
		<?php echo $form->error($model,'user_address2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_suburb'); ?>
		<?php echo $form->textField($model,'user_suburb',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'user_suburb'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_state'); ?>
		<?php echo $form->textField($model,'user_state',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'user_state'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_postcode'); ?>
		<?php echo $form->textField($model,'user_postcode',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'user_postcode'); ?>
	</div>
	
	<?php if($model->Customer): $Customer=$model->Customer; ?>
	
	<h2>Customer Details</h2>
	
	<div class="row">
		<?php echo $form->labelEx($Customer,'location_id'); ?>
		<?php echo $form->textField($Customer,'location_id'); ?>
		<?php echo $form->error($Customer,'location_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($Customer,'customer_notes'); ?>
		<?php echo $form->textArea($Customer,'customer_notes',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($Customer,'customer_notes'); ?>
	</div>
	
	<?php endif; ?>

	<?php if($model->Grower): $Grower=$model->Grower; ?>
	
	<h2>Grower Details</h2>
	
	<div class="row">
		<?php echo $form->labelEx($Grower,'grower_website'); ?>
		<?php echo $form->textField($Grower,'grower_website',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($Grower,'grower_website'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($Grower,'grower_distance_kms'); ?>
		<?php echo $form->textField($Grower,'grower_distance_kms',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($Grower,'grower_distance_kms'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($Grower,'grower_bank_account_name'); ?>
		<?php echo $form->textField($Grower,'grower_bank_account_name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($Grower,'grower_bank_account_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($Grower,'grower_bank_bsb'); ?>
		<?php echo $form->textField($Grower,'grower_bank_bsb',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($Grower,'grower_bank_bsb'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($Grower,'grower_bank_acc'); ?>
		<?php echo $form->textField($Grower,'grower_bank_acc',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($Grower,'grower_bank_acc'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($Grower,'grower_certification_status'); ?>
		<?php echo $form->textField($Grower,'grower_certification_status',array('size'=>60,'maxlength'=>150)); ?>
		<?php echo $form->error($Grower,'grower_certification_status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($Grower,'grower_order_days'); ?>
		<?php echo $form->textField($Grower,'grower_order_days',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($Grower,'grower_order_days'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($Grower,'grower_produce'); ?>
		<?php echo $form->textArea($Grower,'grower_produce',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($Grower,'grower_produce'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($Grower,'grower_notes'); ?>
		<?php echo $form->textArea($Grower,'grower_notes',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($Grower,'grower_notes'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($Grower,'grower_payment_details'); ?>
		<?php echo $form->textArea($Grower,'grower_payment_details',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($Grower,'grower_payment_details'); ?>
	</div>
	
	<?php endif; ?>

	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>
	
	
<?php $this->endWidget(); ?>

</div><!-- form -->