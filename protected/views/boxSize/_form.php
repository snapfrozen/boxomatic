<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'box-size-form',
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
	'enableAjaxValidation'=>false,
)); ?>

<fieldset>
	
	<legend>BoxSize Form</legend>

	<?php if($form->errorSummary($model)): ?>

	<div class="large-12 columns">
		<div data-alert class="alert-box alert">
		 <?php echo $form->errorSummary($model); ?>
		 <a href="#" class="close">&times;</a>
		</div>
	</div>

	<?php endif; ?>

	<div class="row">
		<div class="large-3 columns">
			<?php echo $form->labelEx($model,'box_size_name'); ?>
			<?php echo $form->textField($model,'box_size_name',array('size'=>45,'maxlength'=>45)); ?>
			<?php echo $form->error($model,'box_size_name'); ?>
		</div>
		<div class="large-3 columns">
			<?php echo $form->labelEx($model,'box_size_value'); ?>
			<?php echo $form->textField($model,'box_size_value',array('size'=>7,'maxlength'=>7)); ?>
			<?php echo $form->error($model,'box_size_value'); ?>
		</div>
		<div class="large-3 columns">
			<?php echo $form->labelEx($model,'box_size_markup'); ?>
			<?php echo $form->textField($model,'box_size_markup',array('size'=>7,'maxlength'=>7)); ?>
			<?php echo $form->error($model,'box_size_markup'); ?>
		</div>
		<div class="large-3 columns">
			<?php echo $form->labelEx($model,'box_size_price'); ?>
			<?php echo $form->textField($model,'box_size_price',array('size'=>7,'maxlength'=>7)); ?>
			<?php echo $form->error($model,'box_size_price'); ?>
		</div>
	</div>
	
	<div class="row">
		<div class="large-4 columns">
			<?php echo $form->labelEx($model,'image'); ?>
			<?php echo $form->fileField($model,'image'); ?>
			<?php echo $form->error($model,'image'); ?>
			<?php if(!empty($model->image)): ?>
				<?php echo CHtml::image($this->createUrl('boxSize/image',array('id'=>$model->id,'size'=>'medium'))); ?>
			<?php else: ?>
				<?php echo CHtml::image($this->createUrl('boxSize/image')); ?>
			<?php endif; ?>
		</div>
		<div class="large-8 columns">
			<?php echo $form->labelEx($model,'description'); ?>
			<?php echo $form->textArea($model,'description'); ?>
			<?php echo $form->error($model,'description'); ?>
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

