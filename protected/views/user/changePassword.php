<h1><?php echo $model->full_name; ?></h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>
	
<!--	<div class="row">
		<?php echo $form->labelEx($model,'password_current'); ?>
		<?php echo $form->passwordField($model,'password_current',array('size'=>60,'maxlength'=>255,'value'=>'')); ?>
		<?php echo $form->error($model,'password_current'); ?>
	</div>-->

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>60,'maxlength'=>255,'value'=>'')); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password_repeat'); ?>
		<?php echo $form->passwordField($model,'password_repeat',array('size'=>60,'maxlength'=>255,'value'=>'')); ?>
		<?php echo $form->error($model,'password_repeat'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->