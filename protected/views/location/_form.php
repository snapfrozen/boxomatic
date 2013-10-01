<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'location-form',
	'enableAjaxValidation'=>false,
)); ?>

<fieldset>
	<legend>Update Location Form</legend>

	<?php if($form->errorSummary($model)): ?>

	<div class="large-12 columns">
		<div data-alert class="alert-box alert">
		 <?php echo $form->errorSummary($model); ?>
		 <a href="#" class="close">&times;</a>
		</div>
	</div>

	<?php endif; ?>

	<div class="large-12 columns">
		<?php echo $form->labelEx($model,'location_name'); ?>
		<?php echo $form->textField($model,'location_name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'location_name'); ?>
	</div>
	<div class="large-12 columns">
		<?php echo $form->labelEx($model,'location_delivery_value'); ?>
		<?php echo $form->textField($model,'location_delivery_value',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'location_delivery_value'); ?>
	</div>
	<div class="large-12 columns">
		<label for="">
			<input type='checkbox' name='is_pickup' value='<?php echo $model->is_pickup ?>' />
			Is Pickup?
		</label>
		<?php echo $form->error($model,'is_pickup'); ?>
	</div>
	<div class="large-12 columns">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'button')); ?>
	</div>
</fieldset>



<?php $this->endWidget(); ?>
