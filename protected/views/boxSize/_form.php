<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'box-size-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'box_size_name'); ?>
		<?php echo $form->textField($model,'box_size_name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'box_size_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'box_size_value'); ?>
		<?php echo $form->textField($model,'box_size_value',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'box_size_value'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'box_size_markup'); ?>
		<?php echo $form->textField($model,'box_size_markup',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'box_size_markup'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'box_size_price'); ?>
		<?php echo $form->textField($model,'box_size_price',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'box_size_price'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->