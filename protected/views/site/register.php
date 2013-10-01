<?php 
	$cs = Yii::app()->clientScript;
	// $cs->registerCoreScript('jquery');
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/pages/site/register.js', CClientScript::POS_END);
?>

<?php if(Yii::app()->user->hasFlash('register')): ?>
<div data-alert class="alert-box">
  <?php echo Yii::app()->user->getFlash('register'); ?>
  <a href="#" class="close">&times;</a>
</div>
<?php else: ?>
<?php 
	$form=$this->beginWidget('CActiveForm', array(
		'id'=>'registration-form',
		'enableClientValidation'=>false,
		// 'htmlOptions' => array('class' => 'custom'),
		'clientOptions'=>array(
		'validateOnSubmit'=>true,
		),
	)); 
?>
<div class="row">
	<div class="large-12 columns">
		<h1>Register</h1>
	</div>
	<fieldset>
		<legend>Registration Form</legend>
		<?php if($not_human): ?>
		<div class="row">
			<div class="large-12 columns">
				<div data-alert class="alert-box alert">
				 Please verify that you are human
				 <a href="#" class="close">&times;</a>
				</div>
			</div>
		</div>
		<?php endif; ?>
		<div class="row">
			<div class="large-6 columns">
				<?php echo $form->labelEx($model,'first_name'); ?>
				<?php echo $form->textField($model,'first_name',array('maxlength'=>45)); ?>
				<?php echo $form->error($model,'first_name'); ?>
			</div>
			<div class="large-6 columns">
				<?php echo $form->labelEx($model,'last_name'); ?>
				<?php echo $form->textField($model,'last_name',array('maxlength'=>45)); ?>
				<?php echo $form->error($model,'last_name'); ?>
			</div>
		</div>
		<div class="row">
			<div class="large-12 columns">
				<?php echo $form->labelEx($model,'user_email'); ?>
				<?php echo $form->textField($model,'user_email',array('maxlength'=>255)); ?>
				<?php echo $form->error($model,'user_email'); ?>
			</div>
		</div>
		<div class="row">
			<div class="large-6 columns">
				<?php echo $form->labelEx($model,'password'); ?>
				<?php echo $form->passwordField($model,'password',array('maxlength'=>255)); ?>
				<?php echo $form->error($model,'password'); ?>
			</div>
			<div class="large-6 columns">
				<?php echo $form->labelEx($model,'password_repeat'); ?>
				<?php echo $form->passwordField($model,'password_repeat',array('maxlength'=>255)); ?>
				<?php echo $form->error($model,'password_repeat'); ?>
			</div>
		</div>
		<div class="row">
			<div class="large-6 columns">
				<?php echo $form->labelEx($model,'user_phone'); ?>
				<?php echo $form->textField($model,'user_phone',array('maxlength'=>45)); ?>
				<?php echo $form->error($model,'user_phone'); ?>
			</div>
			<div class="large-6 columns">
				<?php echo $form->labelEx($model,'user_mobile'); ?>
				<?php echo $form->textField($model,'user_mobile',array('maxlength'=>45)); ?>
				<?php echo $form->error($model,'user_mobile'); ?>
			</div>
		</div>
		<div class="row">
			<div class="large-12 columns">
				<?php echo $form->labelEx($model,'user_address'); ?>
				<?php echo $form->textArea($model,'user_address',array('maxlength'=>150)); ?>
				<?php echo $form->error($model,'user_address'); ?>
			</div>
		</div>

		<!-- <div class="row">
			<div class="large-12 columns">
				<?php echo $form->labelEx($model,'user_address2'); ?>
				<?php echo $form->textArea($model,'user_address2',array('maxlength'=>150)); ?>
				<?php echo $form->error($model,'user_address2'); ?>
			</div>
		</div> -->

		<div class="row">
			<div class="large-4 columns">
				<?php echo $form->labelEx($model,'user_state'); ?>
				<?php echo $form->dropDownList($model,'user_state',Yii::app()->params['states']); ?>
				<?php echo $form->error($model,'user_state'); ?>
			</div>

			<div class="large-4 columns">
				<?php echo $form->labelEx($model,'user_state'); ?>
				<?php echo $form->dropDownList($model,'user_state',Yii::app()->params['states']); ?>
				<?php echo $form->error($model,'user_state'); ?>
			</div>

			<div class="large-4 columns">
				<?php echo $form->labelEx($model,'user_postcode'); ?>
				<?php echo $form->textField($model,'user_postcode',array('size'=>45,'maxlength'=>45)); ?>
				<?php echo $form->error($model,'user_postcode'); ?>
			</div>
		</div>

		<div class="row">
			<div class="large-12 columns">
				<?php $Customer=new Customer; ?>
				<?php echo $form->labelEx($Customer,'location_id'); ?>
				<?php echo $form->dropDownList($Customer,'location_id',CHtml::listData(Location::model()->findAll(),'location_id','location_and_delivery')); ?>
				<?php echo $form->error($Customer,'location_id'); ?>
			</div>
		</div>

		<?php /*if(CCaptcha::checkRequirements()): ?>
		<div class="row">
			<div class="large-6 columns">
				<?php echo $form->labelEx($model,'verifyCode'); ?>
				<?php echo $form->textField($model,'verifyCode'); ?>
				<?php echo $form->error($model,'verifyCode'); ?>
			</div>
			<div class="large-6 columns">
				<?php $this->widget('CCaptcha'); ?>
				<p>Please enter the letters as they are shown in the image above. Letters are not case-sensitive.</p>
			</div>
		</div>
		<?php endif;*/ ?>

		<div class="row">
			<div class="large-12 columns">
				<input type="submit" value="Submit" class="button">
			</div>
		</div>
	</fieldset>
</div>
<?php $this->endWidget(); ?>

<?php endif; ?>