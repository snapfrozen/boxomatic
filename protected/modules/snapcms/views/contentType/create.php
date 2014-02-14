<div class="form row">

<?php $form=$this->beginWidget('SnapActiveForm', array(
	'id'=>'content-type-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('class'=>'col-md-12'),
)); ?>
	
	<?php echo $form->errorSummary($model); ?>

	<div class="form-group <?php echo $model->hasErrors('name') ? 'has-error' : ''; ?>">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name',array('class'=>'help-block')); ?>
	</div>
	
	<div class="form-group <?php echo $model->hasErrors('id') ? 'has-error' : ''; ?>">
		<?php echo $form->labelEx($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'id',array('class'=>'help-block')); ?>
	</div>
	
	<div class="buttons">
		<?php echo CHtml::submitButton('Save', array('class'=>'btn btn-primary btn-lg')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->