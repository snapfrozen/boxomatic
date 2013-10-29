
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

<div class="row">
	<fieldset>
		<legend>Identification Numbers</legend>
		<div class="large-4 columns">
			<?php echo $form->label($model,'id'); ?>
			<?php echo $form->textField($model,'id'); ?>
		</div>
		<div class="large-4 columns">
			<?php echo $form->label($model,'customer_id'); ?>
			<?php echo $form->textField($model,'customer_id'); ?>
		</div>
		<div class="large-4 columns">
			<?php echo $form->label($model,'grower_id'); ?>
			<?php echo $form->textField($model,'grower_id'); ?>
		</div>
	</fieldset>
	<fieldset>
		<legend>Personal Information</legend>
		<div class="large-6 columns">
			<?php echo $form->label($model,'first_name'); ?>
			<?php echo $form->textField($model,'first_name',array('size'=>45,'maxlength'=>45)); ?>
		</div>
		<div class="large-6 columns">
			<?php echo $form->label($model,'last_name'); ?>
			<?php echo $form->textField($model,'last_name',array('size'=>45,'maxlength'=>45)); ?>
		</div>
		<div class="large-12 columns">
			<?php echo $form->label($model,'user_email'); ?>
			<?php echo $form->textField($model,'user_email',array('size'=>60,'maxlength'=>255)); ?>
		</div>
		<div class="large-6 columns">
			<?php echo $form->label($model,'user_phone'); ?>
			<?php echo $form->textField($model,'user_phone',array('size'=>45,'maxlength'=>45)); ?>
		</div>
		<div class="large-6 columns">
			<?php echo $form->label($model,'user_mobile'); ?>
			<?php echo $form->textField($model,'user_mobile',array('size'=>45,'maxlength'=>45)); ?>
		</div>
		<div class="large-12 columns">
			<?php echo $form->label($model,'user_address'); ?>
			<?php echo $form->textArea($model,'user_address',array('size'=>60,'maxlength'=>150)); ?>
		</div>
		<div class="large-4 columns">
			<?php echo $form->label($model,'user_suburb'); ?>
			<?php echo $form->textField($model,'user_suburb',array('size'=>45,'maxlength'=>45)); ?>
		</div>
		<div class="large-4 columns">
			<?php echo $form->label($model,'user_state'); ?>
			<?php echo $form->textField($model,'user_state',array('size'=>45,'maxlength'=>45)); ?>
			
		</div>
		<div class="large-4 columns">
			<?php echo $form->label($model,'user_postcode'); ?>
			<?php echo $form->textField($model,'user_postcode',array('size'=>45,'maxlength'=>45)); ?>
		</div>
		<div class="large-12 columns">
			<?php echo $form->label($model,'search_customer_notes'); ?>
			<?php echo $form->textArea($model,'search_customer_notes',array('size'=>45,'maxlength'=>45)); ?>
		</div>
	</fieldset>
	<div class="large-12 columns">
		<div class="right">
			<?php echo CHtml::submitButton('Search', array('class' => 'button')); ?>
		</div>
	</div>
</div>

<?php $this->endWidget(); ?>
