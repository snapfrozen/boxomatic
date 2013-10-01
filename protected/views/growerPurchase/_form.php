<?php
$cs=Yii::app()->clientScript;
$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/ui-lightness/jquery-ui.css');
$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/growerpurchase/_form.js',CClientScript::POS_END);
?>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'grower-purchase-form',
	'enableAjaxValidation'=>false,
)); ?>
	
<fieldset>
	<legend>GrowerItem Registration Form</legend>

	<?php if($form->errorSummary($model)): ?>

	<div class="large-12 columns">
		<div data-alert class="alert-box alert">
		 <?php echo $form->errorSummary($model); ?>
		 <a href="#" class="close">&times;</a>
		</div>
	</div>

	<?php endif; ?>
	
	<div class="large-12 columns">
		<?php echo CHtml::label('Grower','grower_id'); ?>
		<?php echo CHtml::dropDownList('grower_id',CHtml::value($model,'growerItem.grower_id'),Grower::getDropdownListItems(),array('class'=>'chosen')); ?>
		<?php echo CHtml::hiddenField('grower_update_url',$this->createUrl('grower/getListItems')); ?>
	</div>

	<div class="large-12 columns">
		<?php echo $form->labelEx($model,'grower_item_id'); ?>
		<?php if($model->growerItem): ?>
			<?php echo $form->dropDownList($model,'grower_item_id',GrowerItem::getDropdownListItems($model->growerItem->grower_id),array('class'=>'chosen')); ?>
		<?php else: ?>
			<?php echo $form->dropDownList($model,'grower_item_id',array(),array('class'=>'chosen')); ?>
		<?php endif; ?>
		<?php echo $form->error($model,'grower_item_id'); ?>
	</div>

	<div class="large-12 columns">
		<?php echo $form->labelEx($model,'propsed_quantity'); ?>
		<?php echo $form->textField($model,'propsed_quantity',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'propsed_quantity'); ?>
	</div>

	<div class="large-12 columns">
		<?php echo $form->labelEx($model,'propsed_price'); ?>
		<?php echo $form->textField($model,'propsed_price',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'propsed_price'); ?>
	</div>

	<div class="large-12 columns">
		<?php echo $form->labelEx($model,'proposed_delivery_date'); ?>
		<?php 
			$this->widget('zii.widgets.jui.CJuiDatePicker',array(
				'attribute'=>'proposed_delivery_date',
				'model'=>$model,
				'options' => array(
					'dateFormat'=>'yy-mm-dd',
				)
			));	
		?>
		<?php echo $form->error($model,'proposed_delivery_date'); ?>
	</div>

	<div class="large-12 columns">
		<?php echo $form->labelEx($model,'order_notes'); ?>
		<?php echo $form->textArea($model,'order_notes',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'order_notes'); ?>
	</div>

	<div class="large-12 columns">
		<?php echo $form->labelEx($model,'delivered_quantity'); ?>
		<?php echo $form->textField($model,'delivered_quantity',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'delivered_quantity'); ?>
	</div>

	<div class="large-12 columns">
		<?php echo $form->labelEx($model,'final_price'); ?>
		<?php echo $form->textField($model,'final_price',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'final_price'); ?>
	</div>

	<div class="large-12 columns">
		<?php echo $form->labelEx($model,'delivery_nots'); ?>
		<?php echo $form->textArea($model,'delivery_nots',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'delivery_nots'); ?>
	</div>

	<div class="large-12 columns">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class'=>'button')); ?>
	</div>

<?php $this->endWidget(); ?>