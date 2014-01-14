

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'supplier-item-form',
	'enableAjaxValidation'=>false,
)); ?>

<fieldset>
	<legend>Supplier Item Form</legend>

	<?php if($form->errorSummary($model)): ?>

	<div class="large-12 columns">
		<div data-alert class="alert-box alert">
		 <?php echo $form->errorSummary($model); ?>
		 <a href="#" class="close">&times;</a>
		</div>
	</div>

	<?php endif; ?>

	<?php if(!isset($hideSupplier)): ?>
	<div class="large-12 columns">
		<?php echo $form->labelEx($model,'supplier_id'); ?>
		<?php echo $form->dropDownList($model,'supplier_id', CHtml::listData(Supplier::model()->findAll(), 'id', 'name')); ?>
		<?php echo $form->error($model,'supplier_id'); ?>
	</div>
	<?php endif; ?>
	
	<div class="large-4 columns">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
	<div class="large-4 columns">
		<?php echo $form->labelEx($model,'value'); ?>
		<?php echo $form->textField($model,'value',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'value'); ?>
	</div>
	<div class="large-4 columns">
		<?php echo $form->labelEx($model,'unit'); ?>
		<?php echo $form->dropDownList($model,'unit', $model->getUnitList()); ?>
		<?php echo $form->error($model,'unit'); ?>
	</div>
	<div class="large-6 columns">
		<?php echo $form->labelEx($model,'price'); ?>
		<?php echo $form->textField($model,'price'); ?>
        <?php echo $form->error($model,'price'); ?>
	</div>
	<div class="large-6 columns">
	    <?php echo $form->labelEx($model,'wholesale_price'); ?> 
        <?php echo $form->textField($model,'wholesale_price'); ?>   
        <?php echo $form->error($model,'wholesale_price'); ?>
	</div>
	<div class="large-6 columns">
		<?php echo $form->labelEx($model,'available_from'); ?>
		<?php echo $form->dropDownList($model,'available_from', $model->getMonthList()); ?>
        <?php echo $form->error($model,'available_from'); ?>
	</div>
	<div class="large-6 columns">
	    <?php echo $form->labelEx($model,'available_to'); ?> 
        <?php echo $form->dropDownList($model,'available_to', $model->getMonthList()); ?>   
        <?php echo $form->error($model,'available_to'); ?>
	</div>
	
	<?php if(!isset($hideSupplier)): ?>
	<div class="large-12 columns">
		<div class="right">
			<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'button')); ?>
		</div>
	</div>
	<?php endif; ?>
</fieldset>

<?php $this->endWidget(); ?>

