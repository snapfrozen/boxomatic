<h1>Register</h1>

<?php if(Yii::app()->user->hasFlash('register')): ?>
<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('register'); ?>
</div>
<?php else: ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'registration-form',
	'enableClientValidation'=>false,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	

	<div class="row">
		<?php echo $form->labelEx($model,'first_name'); ?>
		<?php echo $form->textField($model,'first_name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'first_name'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'last_name'); ?>
		<?php echo $form->textField($model,'last_name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'last_name'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'user_email'); ?>
		<?php echo $form->textField($model,'user_email',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'user_email'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'password_repeat'); ?>
		<?php echo $form->passwordField($model,'password_repeat',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'password_repeat'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_phone'); ?>
		<?php echo $form->textField($model,'user_phone',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'user_phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_mobile'); ?>
		<?php echo $form->textField($model,'user_mobile',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'user_mobile'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_address'); ?>
		<?php echo $form->textField($model,'user_address',array('size'=>60,'maxlength'=>150)); ?>
		<?php echo $form->error($model,'user_address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_address2'); ?>
		<?php echo $form->textField($model,'user_address2',array('size'=>60,'maxlength'=>150)); ?>
		<?php echo $form->error($model,'user_address2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_suburb'); ?>
		<?php echo $form->textField($model,'user_suburb',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'user_suburb'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_state'); ?>
		<?php echo $form->dropDownList($model,'user_state',Yii::app()->params['states']); ?>
		<?php echo $form->error($model,'user_state'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_postcode'); ?>
		<?php echo $form->textField($model,'user_postcode',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'user_postcode'); ?>
	</div>
	
	<?php $Customer=new Customer; ?>
	<div class="row">
		<?php echo $form->labelEx($Customer,'location_id'); ?>
		<?php echo $form->dropDownList($Customer,'location_id',CHtml::listData(Location::model()->findAll(),'location_id','location_and_delivery')); ?>
		<?php echo $form->error($Customer,'location_id'); ?>
	</div>


	<?php if(CCaptcha::checkRequirements()): ?>
	<div class="row">
		<?php echo $form->labelEx($model,'verifyCode'); ?>
		<div>
		<?php $this->widget('CCaptcha'); ?>
		<?php echo $form->textField($model,'verifyCode'); ?>
		</div>
		<div class="hint">Please enter the letters as they are shown in the image above.
		<br/>Letters are not case-sensitive.</div>
		<?php echo $form->error($model,'verifyCode'); ?>
	</div>
	<?php endif; ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>