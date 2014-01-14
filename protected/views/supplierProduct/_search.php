<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

<fieldset>

	<legend>Supplier Search Form</legend>

	<div class="large-12 columns">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>
	<div class="large-12 columns">
		<?php echo $form->label($model,'supplier_id'); ?>
		<?php echo $form->textField($model,'supplier_id'); ?>
	</div>
	<div class="large-4 columns">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>45,'maxlength'=>45)); ?>
	</div>
	<div class="large-4 columns">
		<?php echo $form->label($model,'value'); ?>
		<?php echo $form->textField($model,'value',array('size'=>7,'maxlength'=>7)); ?>
	</div>
	<div class="large-4 columns">
		<?php echo $form->label($model,'unit'); ?>
		<?php echo $form->textField($model,'unit',array('size'=>2,'maxlength'=>2)); ?>
	</div>
	<div class="large-6 columns">
		<?php echo $form->label($model,'available_from'); ?>
		<?php echo $form->textField($model,'available_from'); ?>
	</div>
	<div class="large-6 columns">
		<?php echo $form->label($model,'available_to'); ?>
		<?php echo $form->textField($model,'available_to'); ?>
	</div>
	<div class="large-12 columns">
		<div class="right">
			<?php echo CHtml::submitButton('Search', array('class' => 'button')); ?>
		</div>
	</div>
</fieldset>

<?php $this->endWidget(); ?>