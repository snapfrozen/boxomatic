

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'grower-item-form',
	'enableAjaxValidation'=>false,
)); ?>

<fieldset>
	<legend>Grower Item Form</legend>

	<?php if($form->errorSummary($model)): ?>

	<div class="large-12 columns">
		<div data-alert class="alert-box alert">
		 <?php echo $form->errorSummary($model); ?>
		 <a href="#" class="close">&times;</a>
		</div>
	</div>

	<?php endif; ?>

	<?php if(!isset($hideGrower)): ?>
	<div class="large-12 columns">
		<?php echo $form->labelEx($model,'grower_id'); ?>
		<?php echo $form->dropDownList($model,'grower_id', CHtml::listData(Grower::model()->findAll(), 'grower_id', 'grower_name')); ?>
		<?php echo $form->error($model,'grower_id'); ?>
	</div>
	<?php endif; ?>
	
	<div class="large-4 columns">
		<?php echo $form->labelEx($model,'item_name'); ?>
		<?php echo $form->textField($model,'item_name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'item_name'); ?>
	</div>
	<div class="large-4 columns">
		<?php echo $form->labelEx($model,'item_value'); ?>
		<?php echo $form->textField($model,'item_value',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'item_value'); ?>
	</div>
	<div class="large-4 columns">
		<?php echo $form->labelEx($model,'item_unit'); ?>
		<?php echo $form->dropDownList($model,'item_unit', $model->getUnitList()); ?>
		<?php echo $form->error($model,'item_unit'); ?>
	</div>
	<div class="large-6 columns">
		<?php echo $form->labelEx($model,'item_available_from'); ?>
		<?php echo $form->dropDownList($model,'item_available_from', $model->getMonthList()); ?>
        <?php echo $form->error($model,'item_available_from'); ?>
	</div>
	<div class="large-6 columns">
	    <?php echo $form->labelEx($model,'item_available_to'); ?> 
        <?php echo $form->dropDownList($model,'item_available_to', $model->getMonthList()); ?>   
        <?php echo $form->error($model,'item_available_to'); ?>
	</div>
	
	<?php if(!isset($hideGrower)): ?>
	<div class="large-12 columns">
		<div class="right">
			<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'button')); ?>
		</div>
	</div>
	<?php endif; ?>
</fieldset>

<?php $this->endWidget(); ?>

