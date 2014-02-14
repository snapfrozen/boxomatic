	<?php
/* @var $this MenuItemController */
/* @var $model MenuItem */
/* @var $form CActiveForm */
?>

<div class="form row">

<?php $form=$this->beginWidget('SnapActiveForm', array(
	'id'=>'menu-item-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('class'=>'col-md-6'),
)); ?>
	
	<?php echo $form->errorSummary($model); ?>
	
	<div class="form-group <?php echo $model->hasErrors('menu_id') ? 'has-error' : ''; ?>">
		<?php echo $form->labelEx($model,'menu_id'); ?>
		<?php echo $form->dropDownList($model,'menu_id',Menu::getDropDownList()); ?>
		<?php echo $form->error($model,'menu_id',array('class'=>'help-block')); ?>
	</div>

	<div class="form-group <?php echo $model->hasErrors('path') ? 'has-error' : ''; ?>">
		<?php echo $form->labelEx($model,'path'); ?>
		<?php echo $form->textField($model,'path',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'path',array('class'=>'help-block')); ?>
	</div>

	<div class="form-group <?php echo $model->hasErrors('title') ? 'has-error' : ''; ?>">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'title',array('class'=>'help-block')); ?>
	</div>

	<?php if($model->Menu): ?>
	<div class="form-group <?php echo $model->hasErrors('parent') ? 'has-error' : ''; ?>">
		<?php echo $form->labelEx($model,'parent'); ?>
		<?php echo $form->dropDownList($model,'parent',$model->Menu->getItemDropDownList()); ?>
		<?php echo $form->error($model,'parent',array('class'=>'help-block')); ?>
	</div>
	<?php endif; ?>

	<div class="form-group <?php echo $model->hasErrors('external_path') ? 'has-error' : ''; ?>">
		<?php echo $form->labelEx($model,'external_path'); ?>
		<?php echo $form->textField($model,'external_path',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'external_path',array('class'=>'help-block')); ?>
	</div>

	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class'=>'btn btn-primary btn-lg')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->