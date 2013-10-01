<?php
	$cs=Yii::app()->clientScript;
	$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/ui-lightness/jquery-ui.css');
	$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/chosen.css');
	$cs->registerCoreScript('jquery.ui');
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/ui.datepicker.min.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/chosen.jquery.min.js', CClientScript::POS_END);
	
Yii::app()->clientScript->registerScript('initPageSize',<<<EOD
$('.change-pageSize').live('change', function() {
	$.fn.yiiGridView.update('customer-payment-grid',{ data:{ pageSize: $(this).val() }})
});
EOD
,CClientScript::POS_READY);
?>

<div class="row">
	<div class="large-12 columns">
		<h1>Enter Customer Payment</h1>
	</div>
	<div class="large-12 columns">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'customer-payment-form',
			'enableAjaxValidation'=>false,
		)); ?>

		<fieldset>
			<legend>Customer Payment Form</legend>
			<?php if($form->errorSummary($model)): ?>
			<div class="alert-box alert"><?php echo $form->error($model,'customer_id'); ?><a href="#" class="close">&times;</a></div>
			<?php endif; ?>
			<div class="large-12 columns">
				<?php echo $form->labelEx($model,'customer_id'); ?>
				<?php echo $form->dropDownList($model,'customer_id',CHtml::listData(Customer::model()->with('User')->findAll(array('order'=>'first_name, last_name')),'customer_id','User.full_name_and_balance'),array('class'=>'chosen')); ?>
				<?php echo $form->error($model,'customer_id'); ?>
			</div>
			<div class="large-4 columns">
				<?php echo $form->labelEx($model,'payment_type'); ?>
				<?php echo $form->dropDownList($model,'payment_type',Yii::app()->params['paymentTypes']); ?>
				<?php echo $form->error($model,'payment_type'); ?>
			</div>
			<div class="large-4 columns">
				<?php echo $form->labelEx($model,'payment_date'); ?>
				<?php echo $form->textField($model,'payment_date',array('class'=>'datepicker')); ?>
				<?php echo $form->error($model,'payment_date'); ?>
			</div>
			<div class="large-4 columns">
				<?php echo $form->labelEx($model,'payment_value'); ?>
				<?php echo $form->textField($model,'payment_value',array('size'=>7,'maxlength'=>7)); ?>
				<?php echo $form->error($model,'payment_value'); ?>
			</div>
			<div class="large-12 columns">
				<?php echo $form->labelEx($model,'payment_note'); ?>
				<?php echo $form->textArea($model,'payment_note',array('rows'=>5, 'cols'=>30,'maxlength'=>1000)); ?>
				<?php echo $form->error($model,'payment_note'); ?>
			</div>
			<div class="large-12 columns">
				<div class="right">
					<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'button')); ?>
				</div>
			</div>
		</fieldset>
		<?php $this->endWidget(); ?>
	</div>

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
		$('.chosen').chosen();
		");
	?>

	<div class="large-12 columns">
		<h1>Search Customer Payments</h1>
		<?php $dataProvider=$search_model->search(); ?>
		<?php $pageSize=Yii::app()->user->getState('pageSize',10); ?>
		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'customer-payment-grid',
			'cssFile' => '', 
			'dataProvider'=>$dataProvider,
			'filter'=>$search_model,
			'summaryText'=>'Displaying {start}-{end} of {count} result(s). ' .
			CHtml::dropDownList(
				'pageSize',
				$pageSize,
				array(5=>5,10=>10,20=>20,50=>50,100=>100),
				array('class'=>'change-pageSize')) .
			' rows per page',
			'columns'=>array(
				array(
					'name'=>'customer_user_id',
					'value'=>'$data->Customer && $data->Customer->User ? $data->Customer->User->id : ""'
				),
				array(
					'name'=>'customer_first_name',
					'value'=>'$data->Customer && $data->Customer->User ? $data->Customer->User->first_name : ""'
				),
				array(
					'name'=>'customer_last_name',
					'value'=>'$data->Customer && $data->Customer->User ? $data->Customer->User->last_name : ""'
				),
				'payment_type',
				'payment_value',
				'payment_date',
				'payment_note',
				array(
					'class'=>'CButtonColumn',
					'template'=>'{view}'
				),
			),
		)); ?>
	</div>
</div>