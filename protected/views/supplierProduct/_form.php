

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'supplier-item-form',
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
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
		<?php echo $form->labelEx($model,'available_from'); ?>
		<?php echo $form->dropDownList($model,'available_from', $model->getMonthList()); ?>
        <?php echo $form->error($model,'available_from'); ?>
	</div>
	<div class="large-6 columns">
	    <?php echo $form->labelEx($model,'available_to'); ?> 
        <?php echo $form->dropDownList($model,'available_to', $model->getMonthList()); ?>   
        <?php echo $form->error($model,'available_to'); ?>
	</div>
	<div class="large-4 columns">
		<?php echo $form->labelEx($model,'image'); ?>
		<?php echo $form->fileField($model,'image'); ?>
        <?php echo $form->error($model,'image'); ?>
		<?php if(!empty($model->image)): ?>
			<?php echo CHtml::image($this->createUrl('supplierProduct/image',array('id'=>$model->id,'size'=>'medium'))); ?>
		<?php else: ?>
			<p>No image</p>
		<?php endif; ?>
	</div>
	<div class="large-8 columns">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>10,'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
		
		<h3>Categories</h3>
		<ul class="categories">
			<?php echo Category::model()->getCategoryTreeForm(Category::supplierProductRootID, $model); ?>
		</ul>
	</div>

	<?php if(!isset($hideSupplier)): ?>
	<div class="large-12 columns">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'button')); ?>
	</div>
	<?php endif; ?>
</fieldset>

<?php $this->endWidget(); ?>

