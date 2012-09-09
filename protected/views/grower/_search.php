<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
	
	<div class="row">
		<?php echo $form->label($model,'grower_name'); ?>
		<?php echo $form->textField($model,'grower_name'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'grower_suburb'); ?>
		<?php echo $form->textField($model,'grower_suburb'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'grower_state'); ?>
		<?php echo $form->textField($model,'grower_state'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'grower_produce'); ?>
		<?php echo $form->textField($model,'grower_produce'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->