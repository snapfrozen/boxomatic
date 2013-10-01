<?php
/* @var $this CustomerLocationController */
/* @var $model CustomerLocation */
/* @var $form CActiveForm */
?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'customer-location-form',
	'enableAjaxValidation'=>false,
)); ?>

<fieldset>

	<legend>Location Form</legend>
	
	<?php if($form->error($model, 'customer_id')): ?>
	<div class="alert-box alert"><?php echo $form->error($model,'customer_id'); ?><a href="#" class="close">&times;</a></div>
	<?php endif; ?>

	<?php echo $form->hiddenField($model,'customer_id'); ?>

	<div class="large-12 columns">
		<?php echo $form->labelEx($model,'location_id'); ?>
		<?php echo $form->dropDownList($model,'location_id',Location::model()->getDeliveryList(),array('prompt'=>' - Select - ')); ?>
		<?php echo $form->error($model,'location_id'); ?>
	</div>

	<div class="large-12 columns">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textArea($model,'address',array('size'=>60,'maxlength'=>150)); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>

	<div class="large-4 columns">
		<?php echo $form->labelEx($model,'suburb'); ?>
		<?php echo $form->textField($model,'suburb',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'suburb'); ?>
	</div>
	<div class="large-4 columns">
		<?php echo $form->labelEx($model,'state'); ?>
		<?php echo $form->dropDownList($model,'state',Yii::app()->params['states']); ?>
		<?php echo $form->error($model,'state'); ?>
	</div>
	<div class="large-4 columns">
		<?php echo $form->labelEx($model,'postcode'); ?>
		<?php echo $form->textField($model,'postcode',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'postcode'); ?>
	</div>

	<div class="large-12 columns">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>

	<div class="large-12 columns">
		<div class="right">
			<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'button')); ?>
		</div>
	</div>

</fieldset>
	



<!-- <div class="row">
	<?php echo $form->labelEx($model,'address2'); ?>
	<?php echo $form->textField($model,'address2',array('size'=>60,'maxlength'=>150)); ?>
	<?php echo $form->error($model,'address2'); ?>
</div> -->


<?php $this->endWidget(); ?>
