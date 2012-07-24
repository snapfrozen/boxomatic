<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'customer-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'customer_name'); ?>
		<?php echo $form->textField($model,'customer_name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'customer_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'customer_phone'); ?>
		<?php echo $form->textField($model,'customer_phone',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'customer_phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'customer_mobile'); ?>
		<?php echo $form->textField($model,'customer_mobile',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'customer_mobile'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'customer_address'); ?>
		<?php echo $form->textField($model,'customer_address',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'customer_address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'customer_address2'); ?>
		<?php echo $form->textField($model,'customer_address2',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'customer_address2'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'customer_suburb'); ?>
		<?php echo $form->textField($model,'customer_suburb',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'customer_suburb'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'customer_state'); ?>
		<?php echo $form->textField($model,'customer_state',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'customer_state'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'customer_postcode'); ?>
		<?php echo $form->textField($model,'customer_postcode',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'customer_postcode'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'location_id'); ?>
		<?php echo $form->dropDownList($model,'location_id', CHtml::listData(Location::model()->findAll(), 'location_id', 'location_name')); ?>
		<?php echo $form->error($model,'location_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'customer_notes'); ?>
		<?php echo $form->textField($model,'customer_notes',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'customer_notes'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'customer_email'); ?>
		<?php echo $form->textField($model,'customer_email',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'customer_email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'customer_password'); ?>
		<?php echo $form->textField($model,'customer_password',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'customer_password'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->