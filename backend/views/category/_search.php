<?php
/* @var $this CategoryController */
/* @var $Category Category */
/* @var $form CActiveForm */
?>

<div class="wide form">

    <?php $form=$this->beginWidget('application.widgets.SnapActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

                    <?php echo $form->textFieldControlGroup($Category,'id'); ?>

                    <?php echo $form->textFieldControlGroup($Category,'parent'); ?>

                    <?php echo $form->textFieldControlGroup($Category,'name',array('maxlength'=>100)); ?>

                    <?php echo $form->textFieldControlGroup($Category,'description'); ?>

        <div class="form-actions">
        <?php echo BsHtml::submitButton('Search',  array('color' => BsHtml::BUTTON_COLOR_PRIMARY,));?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->