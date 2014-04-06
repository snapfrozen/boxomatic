<div class="wide form">
<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'layout' => BsHtml::FORM_LAYOUT_HORIZONTAL,
	'htmlOptions' => array('class'=>'row')
)); ?>
	<fieldset class="col-md-12">
		<?php echo $form->textFieldControlGroup($model,'first_name'); ?>
		<?php echo $form->textFieldControlGroup($model,'last_name'); ?>
		<?php echo $form->textFieldControlGroup($model,'email'); ?>
		<?php echo $form->textFieldControlGroup($model,'user_phone'); ?>
		<?php echo $form->textFieldControlGroup($model,'user_mobile'); ?>
		<?php echo $form->textFieldControlGroup($model,'user_address'); ?>
		<?php echo $form->textFieldControlGroup($model,'user_suburb'); ?>
		<?php echo $form->textFieldControlGroup($model,'user_state'); ?>
		<?php echo $form->textFieldControlGroup($model,'user_postcode'); ?>
		<?php echo $form->textAreaControlGroup($model,'search_customer_notes'); ?>
	</fieldset>
		
	<div class="form-actions col-md-offset-2 col-md-10">
		<?php echo BsHtml::submitButton(BsHtml::icon(BsHtml::GLYPHICON_SEARCH).' Search', array('color' => BsHtml::BUTTON_COLOR_PRIMARY)); ?>
	</div>
<?php $this->endWidget(); ?>
</div>