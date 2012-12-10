<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'week_id'); ?>
		<?php echo $form->textField($model,'week_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'week_notes'); ?>
		<?php echo $form->textField($model,'week_notes',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->