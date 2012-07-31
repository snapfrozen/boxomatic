<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'box_id'); ?>
		<?php echo $form->textField($model,'box_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'size_id'); ?>
		<?php echo $form->textField($model,'size_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'box_price'); ?>
		<?php echo $form->textField($model,'box_price',array('size'=>7,'maxlength'=>7)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'week_id'); ?>
		<?php echo $form->textField($model,'week_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->