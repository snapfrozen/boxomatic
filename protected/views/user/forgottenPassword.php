<div id="content">

	<h1>Forgotten Password</h1>
	
	<?php if($mailError): ?>
		<p>Problem sending mail.</p>
	<?php elseif($User): ?>
		<p>We have sent you a password renewal email to your email address</p>
	<?php endif ?>
	
	<div class="form">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'forgotten-password-form',
			'enableClientValidation'=>true,
			'clientOptions'=>array(
				'validateOnSubmit'=>true,
			),
		)); ?>

		<div class="row">
			<?php echo $form->labelEx($model,'username'); ?>
			<?php echo $form->textField($model,'username'); ?>
			<?php echo $form->error($model,'username'); ?>
		</div>
		<p class="hint">Enter your username or your email address to start the password renewal process</p>
		<p>&nbsp;</p>
		
		<div class="row buttons">
			<?php echo CHtml::submitButton('Next'); ?>
		</div>

		<?php $this->endWidget(); ?>
	</div><!-- form -->

</div>