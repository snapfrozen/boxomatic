
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'supplier-search',
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

<div class="row">
	<div class="large-12 columns">
		<fieldset>
			<legend>Supplier Search Form</legend>
			<div class="large-6 columns">
				<?php echo $form->label($model,'name'); ?>
				<?php echo $form->textField($model,'name'); ?>
			</div>
			<div class="large-6 columns">
				<?php echo $form->label($model,'item_search'); ?>
				<?php echo $form->textField($model,'item_search'); ?>
			</div>
			<div class="large-12 columns">
				<div class="right">
					<?php echo CHtml::submitButton('Search', array('class' => 'button')); ?>
				</div>
			</div>
		</fieldset>
	</div>
</div>

	
<!--	<div class="row">
		<?php echo $form->label($model,'suburb'); ?>
		<?php echo $form->textField($model,'suburb'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'state'); ?>
		<?php echo $form->textField($model,'state'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'produce'); ?>
		<?php echo $form->textField($model,'produce'); ?>
	</div>-->

<?php $this->endWidget(); ?>