<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'location-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'location_name'); ?>
		<?php echo $form->textField($model,'location_name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'location_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'location_delivery_value'); ?>
		<?php echo $form->textField($model,'location_delivery_value',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'location_delivery_value'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->