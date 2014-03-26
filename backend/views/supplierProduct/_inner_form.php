<?php echo $form->textFieldControlGroup($model,'name',array('size'=>45,'maxlength'=>45)); ?>

<?php if(!isset($hideSupplier)): ?>
	<?php echo $form->dropDownListControlGroup($model,'supplier_id', CHtml::listData(Supplier::model()->findAll(), 'id', 'name'),array('class'=>'chosen')); ?>
	<?php echo $form->dropDownListControlGroup($model,'packing_station_id', CHtml::listData(PackingStation::model()->findAll(), 'id', 'name'),array('class'=>'chosen')); ?>
<?php endif; ?>

<?php echo $form->textFieldControlGroup($model,'value',array('size'=>7,'maxlength'=>7)); ?>
<?php echo $form->dropDownListControlGroup($model,'unit', $model->getUnitList()); ?>

<?php echo $form->dropDownListControlGroup($model,'available_from', $model->getMonthList()); ?>
<?php echo $form->dropDownListControlGroup($model,'available_to', $model->getMonthList()); ?>
<?php echo $form->checkBoxControlGroup($model,'limited_stock'); ?>

<?php echo $form->imageField($model, 'image'); ?>
<?php echo $form->textAreaControlGroup($model, 'description'); ?>

<div class="row">
	<div class="col-md-offset-2 col-md-10">
		<h3>Categories</h3>
		<ul class="categories">
			<?php echo Category::model()->getCategoryTreeForm(SnapUtil::config('boxomatic/supplier_product_root_id'), $model); ?>
		</ul>
	</div>
</div>