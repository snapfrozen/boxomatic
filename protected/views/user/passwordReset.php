<div id="content">

	<h1>Reset your password</h1>
	
	<?php if(!$model): ?>
	
	<p>The reset password url is no longer valid, please <?php echo CHtml::link('generate',array('core/forgottenPassword')) ?> a new one.</p>
	
	<?php else: ?>
	
	<div class="form">
		<?php if(!$updateComplete): ?>
		
		<p>Hi <?php echo $model->first_name; ?> please enter a new password.</p>
		
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'password-reset-form',
			'enableAjaxValidation'=>false,
		)); ?>

		<div class="row">
			<?php echo $form->labelEx($model,'password'); ?>
			<?php echo $form->passwordField($model,'password',array('value'=>'')); ?>
			<?php echo $form->error($model,'password'); ?>
		</div>
		
		<div class="row">
			<?php echo $form->labelEx($model,'password_repeat'); ?>
			<?php echo $form->passwordField($model,'password_repeat'); ?>
			<?php echo $form->error($model,'password_repeat'); ?>
		</div>

		<div class="row buttons">
			<?php echo CHtml::submitButton('Next'); ?>
		</div>

		<?php $this->endWidget(); ?>
		
		<?php else: ?>
		<p>Password has been updated, please <?php echo CHtml::link('login', array('core/login')) ?> to continue to SolarPlus.</p>
		<?php endif; ?>
	</div><!-- form -->

	<?php endif ?>
</div>