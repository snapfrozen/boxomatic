<?php
	$cs=Yii::app()->clientScript;
	$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/redmond/jquery-ui.css');
	$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/chosen.css');
	
	$cs->registerCoreScript('jquery.ui');
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/ui.datepicker.min.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/chosen.jquery.min.js', CClientScript::POS_END);
?>

<h1>Customer Payment</h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'customer-payment-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'customer_id'); ?>
		<?php echo $form->dropDownList($model,'customer_id',CHtml::listData(Customer::model()->with('User')->findAll(),'customer_id','User.user_name'),array('class'=>'chosen')); ?>
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

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->