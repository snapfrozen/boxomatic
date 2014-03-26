<div class="row">
	<div class="large-12 columns">
		<h1><?php echo $model->full_name; ?></h1>
	</div>

	<?php $form=$this->beginWidget('application.widgets.SnapActiveForm', array(
		'id'=>'user-form',
		'enableAjaxValidation'=>false, 
		'htmlOptions' => array('class' => 'custom' )
	)); ?>
	
	<div class="large-8 columns">
		<fieldset>
			<legend>Change Password</legend>
			<?php if($form->errorSummary($model)): ?>

			<div class="large-12 columns">
				<div data-alert class="alert-box alert">
				 <?php echo $form->errorSummary($model); ?>
				 <a href="#" class="close">&times;</a>
				</div>
			</div>

			<?php endif; ?>

			<div class="large-6 columns">
				<?php echo $form->labelEx($model,'password'); ?>
				<?php echo $form->passwordField($model,'password',array('size'=>60,'maxlength'=>255,'value'=>'')); ?>
				<?php echo $form->error($model,'password'); ?>
			</div>
			<div class="large-6 columns">
				<?php echo $form->labelEx($model,'password_repeat'); ?>
				<?php echo $form->passwordField($model,'password_repeat',array('size'=>60,'maxlength'=>255,'value'=>'')); ?>
				<?php echo $form->error($model,'password_repeat'); ?>
			</div>
			<div class="large-12 columns">
				<?php echo CHtml::submitButton('Save', array('class' => 'button')); ?>
			</div>
		</fieldset>

	</div>

	<?php $this->endWidget(); ?>

</div>
