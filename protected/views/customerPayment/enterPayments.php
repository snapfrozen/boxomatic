<?php
	$cs=Yii::app()->clientScript;
	$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/redmond/jquery-ui.css');
	$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/chosen.css');
	
	$cs->registerCoreScript('jquery.ui');
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/ui.datepicker.min.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/chosen.jquery.min.js', CClientScript::POS_END);
?>

<h1>Enter Customer Payment</h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'customer-payment-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'customer_id'); ?>
		<?php echo $form->dropDownList($model,'customer_id',CHtml::listData(Customer::model()->with('User')->findAll(),'customer_id','User.full_name'),array('class'=>'chosen')); ?>
		<?php echo $form->error($model,'customer_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'payment_type'); ?>
		<?php echo $form->dropDownList($model,'payment_type',Yii::app()->params['paymentTypes']); ?>
		<?php echo $form->error($model,'payment_type'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'payment_date'); ?>
		<?php echo $form->textField($model,'payment_date',array('class'=>'datepicker')); ?>
		<?php echo $form->error($model,'payment_date'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'payment_value'); ?>
		<?php echo $form->textField($model,'payment_value',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'payment_value'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'payment_note'); ?>
		<?php echo $form->textArea($model,'payment_note',array('rows'=>5, 'cols'=>30,'maxlength'=>1000)); ?>
		<?php echo $form->error($model,'payment_note'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('customer-payment-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Search Customer Payments</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'customer-payment-grid',
	'dataProvider'=>$search_model->search(),
	'filter'=>$search_model,
	'columns'=>array(
		'payment_type',
		'payment_value',
		'payment_date',
		'payment_note',
		'Customer.User.first_name',
		'Customer.User.last_name',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>


</div><!-- form -->