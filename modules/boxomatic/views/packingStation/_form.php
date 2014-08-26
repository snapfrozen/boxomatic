<?php
/* @var $this PackingStationController */
/* @var $model PackingStation */
/* @var $form CActiveForm */
?>

<div class="form">
<?php $form=$this->beginWidget('application.widgets.SnapActiveForm', array(
	'id'=>'packing-station-form',
	'enableAjaxValidation'=>false,
	'layout' => BsHtml::FORM_LAYOUT_HORIZONTAL,
	'htmlOptions'=>array('class'=>'row'),
)); ?>

	<div class="col-lg-9 clearfix">
		<?php echo $form->textFieldControlGroup($model,'name',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<?php echo $this->renderPartial('//layouts/_form_sidebar'); ?>

<?php $this->endWidget(); ?>

</div><!-- form -->