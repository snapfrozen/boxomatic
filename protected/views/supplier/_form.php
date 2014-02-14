
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'supplier-form',
	'enableAjaxValidation'=>false,
)); ?>

<fieldset>
	<legend>Supplier Registration Form</legend>
	
	<?php if($form->errorSummary($model)): ?>

	<div class="large-12 columns">
		<div data-alert class="alert-box alert">
		 <?php echo $form->errorSummary($model); ?>
		 <a href="#" class="close">&times;</a>
		</div>
	</div>

	<?php endif; ?>

	<div class="large-12 columns">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name'); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="large-12 columns">
		<?php echo $form->labelEx($model,'company_name'); ?>
		<?php echo $form->textField($model,'company_name'); ?>
		<?php echo $form->error($model,'company_name'); ?>
	</div>

	<div class="large-12 columns">
		<?php echo $form->labelEx($model,'Ordering'); ?>
		<?php echo $form->textField($model,'Ordering'); ?>
		<?php echo $form->error($model,'Ordering'); ?>
	</div>

	<div class="large-6 columns">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email'); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>
	<div class="large-6 columns">
		<?php echo $form->labelEx($model,'website'); ?>
		<?php echo $form->textField($model,'website'); ?>
		<?php echo $form->error($model,'website'); ?>
	</div>

	<div class="large-6 columns">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone'); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>
	<div class="large-6 columns">
		<?php echo $form->labelEx($model,'mobile'); ?>
		<?php echo $form->textField($model,'mobile'); ?>
		<?php echo $form->error($model,'mobile'); ?>
	</div>

	<div class="large-12 columns">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textField($model,'address'); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>

	<div class="large-4 columns">
		<?php echo $form->labelEx($model,'suburb'); ?>
		<?php echo $form->textField($model,'suburb'); ?>
		<?php echo $form->error($model,'suburb'); ?>
	</div>
	
	<div class="large-4 columns">
		<?php echo $form->labelEx($model,'postcode'); ?>
		<?php echo $form->textField($model,'postcode'); ?>
		<?php echo $form->error($model,'postcode'); ?>
	</div>

	<div class="large-4 columns">
		<?php echo $form->labelEx($model,'state'); ?>
		<?php echo $form->dropDownList($model,'state',Yii::app()->params['states']); ?>
		<?php echo $form->error($model,'state'); ?>
	</div>

	<div class="large-6 columns">
		<?php echo $form->labelEx($model,'ABN'); ?>
		<?php echo $form->textField($model,'ABN'); ?>
		<?php echo $form->error($model,'ABN'); ?>
	</div>

	<div class="large-6 columns">
		<?php echo $form->labelEx($model,'distance_kms'); ?>
		<?php echo $form->textField($model,'distance_kms',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'distance_kms'); ?>
	</div>

	<div class="large-12 columns">
		<?php echo $form->labelEx($model,'bank_account_name'); ?>
		<?php echo $form->textField($model,'bank_account_name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'bank_account_name'); ?>
	</div>

	<div class="large-6 columns">
		<?php echo $form->labelEx($model,'bank_bsb'); ?>
		<?php echo $form->textField($model,'bank_bsb',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'bank_bsb'); ?>
	</div>

	<div class="large-6 columns">
		<?php echo $form->labelEx($model,'bank_acc'); ?>
		<?php echo $form->textField($model,'bank_acc',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'bank_acc'); ?>
	</div>

	<div class="large-6 columns">
		<?php echo $form->labelEx($model,'certification_status'); ?>
		<?php echo $form->textField($model,'certification_status',array('size'=>60,'maxlength'=>150)); ?>
		<?php echo $form->error($model,'certification_status'); ?>
	</div>
	<div class="large-6 columns">
		<?php echo $form->labelEx($model,'order_days'); ?>
		<?php echo $form->textField($model,'order_days',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'order_days'); ?>
	</div>

	<div class="large-12 columns">
		<?php echo $form->labelEx($model,'produce'); ?>
		<?php echo $form->textArea($model,'produce',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'produce'); ?>
	</div>

	<div class="large-12 columns">
		<?php echo $form->labelEx($model,'notes'); ?>
		<?php echo $form->textArea($model,'notes',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'notes'); ?>
	</div>

	<div class="large-12 columns">
		<?php echo $form->labelEx($model,'payment_details'); ?>
		<?php echo $form->textArea($model,'payment_details',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'payment_details'); ?>
	</div>
	<div class="large-6 columns">
		<?php echo $form->labelEx($model,'lattitude'); ?>
		<?php echo $form->textField($model,'lattitude'); ?>
		<?php echo $form->error($model,'lattitude'); ?>
	</div>
	<div class="large-6 columns">
		<?php echo $form->labelEx($model,'longitude'); ?>
		<?php echo $form->textField($model,'longitude'); ?>
		<?php echo $form->error($model,'longitude'); ?>
	</div>
</fieldset>

<div class="large-12 columns">
	<div class="right">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'button')); ?>
	</div>
</div>

<?php $this->endWidget(); ?>