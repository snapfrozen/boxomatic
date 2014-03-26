<?php
/* @var $this CategoryController */
/* @var Category Category */
/* @var $form BSActiveForm */
?>

<div class="form">
    <?php $form=$this->beginWidget('application.widgets.SnapActiveForm', array(
	'id'=>'category-form',
	'enableAjaxValidation'=>false,
	'layout' => BsHtml::FORM_LAYOUT_HORIZONTAL,
	'htmlOptions' => array('class'=>'row'),
)); ?>

	<div class="col-lg-9 clearfix">
		<?php echo $form->dropDownListControlGroup($Category,'parent',Category::getDropDownList(SnapUtil::config('boxomatic/supplier_product_root_id'))); ?>
		<?php echo $form->textFieldControlGroup($Category,'name',array('maxlength'=>100)); ?>
		<?php echo $form->richTextAreaControlGroup($Category,'description'); ?>
	</div>
	
	<?php echo $this->renderPartial('//layouts/_form_sidebar'); ?>
	
    <?php $this->endWidget(); ?>

</div><!-- form -->