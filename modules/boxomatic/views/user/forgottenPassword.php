<div class="row">
	<div class="large-12 columns">
		<h1>Forgotten Password</h1>
		<?php if($mailError): ?>
		<div class="alert-box alert">
			Problem sending mail.
			<a href="#" class="close">&times;</a>
		</div>
		<?php elseif($User): ?>
		<div class="alert-box">
			We have sent you a password renewal email to your email address.
			<a href="#" class="close">&times;</a>
		</div>
		<?php endif; ?>
	</div>
	<div class="large-8 columns">

		<?php $form=$this->beginWidget('application.widgets.SnapActiveForm', array(
			'id'=>'forgotten-password-form',
			'enableClientValidation'=>true,
			'clientOptions'=>array(
				'validateOnSubmit'=>true,
			),
		)); ?>

		<fieldset>
			<legend>Password Retrieval Form</legend>
			<div class="large-12 columns">
				<?php echo $form->labelEx($model,'username'); ?>
				<?php echo $form->textField($model,'username'); ?>
				<?php echo $form->error($model,'username'); ?>
			</div>
			<div class="large-12 columns">
				<p>Enter your username or your email address to start the password renewal process</p>
			</div>
			<div class="large-12 columns">
				<?php echo CHtml::submitButton('Next', array('class' => 'button')); ?>
			</div>
		</fieldset>

		<?php $this->endWidget(); ?>
	</div>
	<div class="large-4 columns">&nbsp;</div>
</div>