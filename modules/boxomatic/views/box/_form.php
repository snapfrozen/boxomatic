<div class="form">

<?php $form=$this->beginWidget('application.widgets.SnapActiveForm', array(
	'id'=>'box-form',
	'enableAjaxValidation'=>false,
	'layout' => BsHtml::FORM_LAYOUT_HORIZONTAL,
	'htmlOptions' => array('class'=>'row')
)); ?>

	<div class="col-lg-9 clearfix">
		<?php echo $form->textFieldControlGroup($model,'variation_name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->textFieldControlGroup($model,'box_price',array('size'=>7,'maxlength'=>7)); ?>
	</div>
	
	<?php echo $this->renderPartial('//layouts/_form_sidebar'); ?>

<?php $this->endWidget(); ?>

</div><!-- form -->