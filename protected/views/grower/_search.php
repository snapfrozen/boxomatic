
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'grower-search',
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

<div class="row">
	<div class="large-12 columns">
		<fieldset>
			<legend>Grower Search Form</legend>
			<div class="large-6 columns">
				<?php echo $form->label($model,'grower_name'); ?>
				<?php echo $form->textField($model,'grower_name'); ?>
			</div>
			<div class="large-6 columns">
				<?php echo $form->label($model,'grower_item_search'); ?>
				<?php echo $form->textField($model,'grower_item_search'); ?>
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
		<?php echo $form->label($model,'grower_suburb'); ?>
		<?php echo $form->textField($model,'grower_suburb'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'grower_state'); ?>
		<?php echo $form->textField($model,'grower_state'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'grower_produce'); ?>
		<?php echo $form->textField($model,'grower_produce'); ?>
	</div>-->

<?php $this->endWidget(); ?>