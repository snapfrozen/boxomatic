<div class="form">
<?php $form=$this->beginWidget('application.widgets.SnapActiveForm', array(
	'id'=>'user-location-form',
	'enableAjaxValidation'=>false,
	'layout' => BsHtml::FORM_LAYOUT_HORIZONTAL,
	'htmlOptions' => array('class'=>'row')
)); ?>
	<div class="col-lg-9 clearfix">
		<?php echo $form->errorSummary($model); ?>
		
		<?php if($form->error($model, 'user_id')): ?>
			<div class="alert alert-danger"><?php echo $form->error($model,'user_id'); ?><a href="#" class="close">&times;</a></div>
		<?php endif; ?>
		<?php echo $form->hiddenField($model,'user_id'); ?>

		<?php echo $form->dropDownListControlGroup($model,'location_id',Location::model()->getDeliveryList(),array('prompt'=>' - Select - ')); ?>			
		<?php echo $form->textFieldControlGroup($model,'address',array('size'=>60,'maxlength'=>150)); ?>
		<?php echo $form->textFieldControlGroup($model,'suburb',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->dropDownListControlGroup($model,'state',SnapUtil::config('boxomatic/states')); ?>
		<?php echo $form->textFieldControlGroup($model,'postcode',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->textFieldControlGroup($model,'phone',array('size'=>45,'maxlength'=>45)); ?>
	</div>
	
	<?php echo $this->renderPartial('//layouts/_form_sidebar'); ?>

<?php $this->endWidget(); ?>
</div>