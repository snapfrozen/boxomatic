<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'week-form',
	'enableAjaxValidation'=>false,
)); ?>

<fieldset>
	<legend>Week Form</legend>

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
			<?php echo $form->labelEx($model,'week_notes'); ?>
			<?php echo $form->textField($model,'week_notes',array('size'=>45,'maxlength'=>45)); ?>
			<?php echo $form->error($model,'week_notes'); ?>
		</div>
	</div>
	<div class="row">
		<div class="large-12 columns">
			<?php echo $form->labelEx($model,'week_disabled'); ?>
			<?php echo $form->checkBox($model,'week_disabled'); ?>
			<?php echo $form->error($model,'week_disabled'); ?>
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
