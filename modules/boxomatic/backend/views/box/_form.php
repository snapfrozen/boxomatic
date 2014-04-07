<div class="form">

<?php $form=$this->beginWidget('application.widgets.SnapActiveForm', array(
	'id'=>'box-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'size_id'); ?>
		<?php echo $form->textField($model,'size_id'); ?>
		<?php echo $form->error($model,'size_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'box_price'); ?>
		<?php echo $form->textField($model,'box_price',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'box_price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'delivery_date_id'); ?>
		<?php echo $form->textField($model,'delivery_date_id'); ?>
		<?php echo $form->error($model,'delivery_date_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->