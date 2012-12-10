<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'week-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'week_notes'); ?>
		<?php echo $form->textField($model,'week_notes',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'week_notes'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'week_disabled'); ?>
		<?php echo $form->checkBox($model,'week_disabled'); ?>
		<?php echo $form->error($model,'week_disabled'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->