<?php
$this->pageTitle=Yii::app()->name . ' - Login';
?>

<div class="row">
	<div class="large-8 columns">
		<h1>Login</h1>
		<p>Please fill out the following form with your login information:</p>

		<?php $form=$this->beginWidget('application.widgets.SnapActiveForm', array(
			'id'=>'login-form',
			'enableClientValidation'=>true,
			'clientOptions'=>array(
				'validateOnSubmit'=>true,
			),
			'htmlOptions' => array('class' => 'custom')
		)); ?>

		<fieldset>
			<!-- <legend>Login Form</legend> -->
			<div class="row">
				<div class="large-12 columns">
					<div class="name-field">
						<?php echo $form->labelEx($model,'username'); ?>
						<?php echo $form->textField($model,'username'); ?>
						<?php echo $form->error($model,'username'); ?>
					</div>
				</div>
				<div class="large-12 columns">
					<div class="password-field">
						<?php echo $form->labelEx($model,'password'); ?>
						<?php echo $form->passwordField($model,'password'); ?>
						<?php echo $form->error($model,'password'); ?>
					</div>
				</div>
				<div class="large-12 columns">
					<input type="submit" class="button" value="Login" />
					<?php echo CHtml::link('Forgot My Password',array('user/forgottenPassword'), array('class' => 'button')); ?>
				</div>
			</div>
		</fieldset>
		<?php $this->endWidget(); ?>
	</div>
</div>
