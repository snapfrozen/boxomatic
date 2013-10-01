<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

<fieldset>

	<legend>Grower Search Form</legend>

	<div class="large-12 columns">
		<?php echo $form->label($model,'item_id'); ?>
		<?php echo $form->textField($model,'item_id'); ?>
	</div>
	<div class="large-12 columns">
		<?php echo $form->label($model,'grower_id'); ?>
		<?php echo $form->textField($model,'grower_id'); ?>
	</div>
	<div class="large-4 columns">
		<?php echo $form->label($model,'item_name'); ?>
		<?php echo $form->textField($model,'item_name',array('size'=>45,'maxlength'=>45)); ?>
	</div>
	<div class="large-4 columns">
		<?php echo $form->label($model,'item_value'); ?>
		<?php echo $form->textField($model,'item_value',array('size'=>7,'maxlength'=>7)); ?>
	</div>
	<div class="large-4 columns">
		<?php echo $form->label($model,'item_unit'); ?>
		<?php echo $form->textField($model,'item_unit',array('size'=>2,'maxlength'=>2)); ?>
	</div>
	<div class="large-6 columns">
		<?php echo $form->label($model,'item_available_from'); ?>
		<?php echo $form->textField($model,'item_available_from'); ?>
	</div>
	<div class="large-6 columns">
		<?php echo $form->label($model,'item_available_to'); ?>
		<?php echo $form->textField($model,'item_available_to'); ?>
	</div>
	<div class="large-12 columns">
		<div class="right">
			<?php echo CHtml::submitButton('Search', array('class' => 'button')); ?>
		</div>
	</div>
</fieldset>

<?php $this->endWidget(); ?>