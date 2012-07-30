<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'box_sizes'); ?>
		<?php echo $form->textField($model,'box_sizes'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'box_size_name'); ?>
		<?php echo $form->textField($model,'box_size_name',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'box_size_value'); ?>
		<?php echo $form->textField($model,'box_size_value',array('size'=>7,'maxlength'=>7)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'box_size_markup'); ?>
		<?php echo $form->textField($model,'box_size_markup',array('size'=>7,'maxlength'=>7)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'box_size_price'); ?>
		<?php echo $form->textField($model,'box_size_price',array('size'=>7,'maxlength'=>7)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->