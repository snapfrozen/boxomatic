<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'grower-item-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'grower_id'); ?>
		<?php echo $form->dropDownList($model,'grower_id', CHtml::listData(Grower::model()->findAll(), 'grower_id', 'grower_name')); ?>
		<?php echo $form->error($model,'grower_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'item_name'); ?>
		<?php echo $form->textField($model,'item_name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'item_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'item_value'); ?>
		$<?php echo $form->textField($model,'item_value',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'item_value'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'item_unit'); ?>
		<?php echo $form->dropDownList($model,'item_unit', $model->getUnitList()); ?>
		<?php echo $form->error($model,'item_unit'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'item_available_from'); ?>
		<?php echo $form->dropDownList($model,'item_available_from', $model->getMonthList()); ?>
        <?php echo $form->error($model,'item_available_from'); ?>
	</div>

	<div class="row">
	    <?php echo $form->labelEx($model,'item_available_to'); ?> 
        <?php echo $form->dropDownList($model,'item_available_to', $model->getMonthList()); ?>   
        <?php echo $form->error($model,'item_available_to'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->