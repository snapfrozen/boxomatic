<div class="form">

<?php $form=$this->beginWidget('application.widgets.SnapActiveForm', array(
	'id'=>'box-size-form',
	'enableAjaxValidation'=>false,
	'layout' => BsHtml::FORM_LAYOUT_HORIZONTAL,
	'htmlOptions' => array('class'=>'row')
)); ?>

	<div class="col-lg-9 clearfix">
		<?php echo $form->textFieldControlGroup($model,'box_size_name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->textFieldControlGroup($model,'box_size_value',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->textFieldControlGroup($model,'box_size_markup',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->textFieldControlGroup($model,'box_size_price',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->textAreaControlGroup($model,'description'); ?>
		<?php echo $form->imageField($model,'image'); ?>
	</div>
	
	<?php echo $this->renderPartial('//layouts/_form_sidebar'); ?>

<?php $this->endWidget(); ?>

</div>

