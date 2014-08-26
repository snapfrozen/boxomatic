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

	<div class="row">
		<div class="large-12 columns">
			<?php echo $form->labelEx($model,'location_id'); ?>
			<?php echo $form->dropDownList($model,'location_id',Location::model()->getDeliveryList(),array('prompt'=>' - Select - ')); ?>
			<?php echo $form->error($model,'location_id'); ?>
		</div>
	</div>

	<div class="row">
		<div class="large-12 columns">
			<?php echo $form->labelEx($model,'address'); ?>
			<?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>150)); ?>
			<?php echo $form->error($model,'address'); ?>
		</div>
	</div>

	<div class="row">
		<div class="large-4 columns">
			<?php echo $form->labelEx($model,'suburb'); ?>
			<?php echo $form->textField($model,'suburb',array('size'=>45,'maxlength'=>45)); ?>
			<?php echo $form->error($model,'suburb'); ?>
		</div>
		<div class="large-4 columns">
			<?php echo $form->labelEx($model,'state'); ?>
			<?php echo $form->dropDownList($model,'state',SnapUtil::config('boxomatic/states')); ?>
			<?php echo $form->error($model,'state'); ?>
		</div>
		<div class="large-4 columns">
			<?php echo $form->labelEx($model,'postcode'); ?>
			<?php echo $form->textField($model,'postcode',array('size'=>45,'maxlength'=>45)); ?>
			<?php echo $form->error($model,'postcode'); ?>
		</div>
	</div>

	<div class="row">
		<div class="large-12 columns">
			<?php echo $form->labelEx($model,'phone'); ?>
			<?php echo $form->textField($model,'phone',array('size'=>45,'maxlength'=>45)); ?>
			<?php echo $form->error($model,'phone'); ?>
		</div>
	</div>

	<div class="row">
		<div class="large-12 columns">
			<div class="right">
				<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'button')); ?>
			</div>
		</div>
	</div>

</fieldset>

<?php $this->endWidget(); ?>