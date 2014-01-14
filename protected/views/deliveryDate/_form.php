<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'delivery-date-form',
	'enableAjaxValidation'=>false,
)); ?>

<fieldset>
	<legend>DeliveryDate Form</legend>

	<?php if($form->errorSummary($model)): ?>

	<div class="large-12 columns">
		<div data-alert class="alert-box alert">
		 <?php echo $form->errorSummary($model); ?>
		 <a href="#" class="close">&times;</a>
		</div>
	</div>

	<?php endif; ?>


	<div class="row">
		<div class="large-12 columns">
			<?php echo $form->labelEx($model,'notes'); ?>
			<?php echo $form->textField($model,'notes',array('size'=>45,'maxlength'=>45)); ?>
			<?php echo $form->error($model,'notes'); ?>
		</div>
	</div>
	<div class="row">
		<div class="large-12 columns">
			<?php echo $form->labelEx($model,'disabled'); ?>
			<?php echo $form->checkBox($model,'disabled'); ?>
			<?php echo $form->error($model,'disabled'); ?>
		</div>
	</div>
	<div class="row">
		<div class="large-12 columns">
			<div class="right">
				<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'button')); ?>
			</div>
		</div>
	</div>
</fieldset>

<?php $this->endWidget(); ?>
