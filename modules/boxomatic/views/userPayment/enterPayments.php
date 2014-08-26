<?php

$baseUrl = $this->createFrontendUrl('/').'/themes/boxomatic/admin';
$cs=Yii::app()->clientScript;

$cs->registerCssFile($baseUrl . '/css/ui-lightness/jquery-ui.css');
$cs->registerCssFile($baseUrl . '/css/chosen.css');
$cs->registerCoreScript('jquery.ui');
$cs->registerScriptFile($baseUrl . '/js/ui.datepicker.min.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/chosen.jquery.min.js', CClientScript::POS_END);
	
$this->breadcrumbs=array(
	'Box-O-Matic'=>array('/snapcms/boxomatic/index'),
	'Customers'=>array('user/customers'),
	'Payments',
);
$this->page_heading = 'Customer Payments';
	
Yii::app()->clientScript->registerScript('initPageSize',<<<EOD
$('.change-pageSize').live('change', function() {
	$.fn.yiiGridView.update('customer-payment-grid',{ data:{ pageSize: $(this).val() }})
});
EOD
,CClientScript::POS_READY);
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
<div class="form row">
<?php $form=$this->beginWidget('application.widgets.SnapActiveForm', array(
	'id'=>'user-payment-form',
	'enableAjaxValidation'=>false,
	'layout' => BsHtml::FORM_LAYOUT_HORIZONTAL,
)); ?>
	<div class="col-lg-9 clearfix">
		<?php echo $form->dropDownListControlGroup($model,'user_id',CHtml::listData(BoxomaticUser::model()->findAll(array('order'=>'first_name, last_name')),'id','full_name_and_balance'),array('class'=>'chosen')); ?>
		<?php echo $form->dropDownListControlGroup($model,'payment_type',SnapUtil::config('boxomatic/paymentTypes')); ?>
		<?php echo $form->dateFieldControlGroup($model,'payment_date'); ?>
		<?php echo $form->textFieldControlGroup($model,'payment_value',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->textAreaControlGroup($model,'payment_note'); ?>
	</div>
	<?php echo $this->renderPartial('//layouts/_form_sidebar'); ?>
<?php $this->endWidget(); ?>
</div>
<div class="row">
	<div class="col-lg-9 clearfix">
		<?php
			$this->beginWidget('bootstrap.widgets.BsPanel', array(
				'title'=>'Search Customer Payments',
				'titleTag'=>'h3',
			));
			?>
			<?php $dataProvider=$search_model->search(); ?>
			<?php $pageSize=Yii::app()->user->getState('pageSize',10); ?>
			<?php $this->widget('backend.widgets.SnapGridView', array(
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
						'value'=>'CHtml::value($data,"User.id")',
					),
					array(
						'name'=>'customer_first_name',
						'value'=>'CHtml::value($data,"User.first_name")',
					),
					array(
						'name'=>'customer_last_name',
						'value'=>'CHtml::value($data,"User.last_name")',
					),
					'payment_type',
					'payment_value:currency',
					'payment_date:date',
					'payment_note',
					array(
						'class'=>'bootstrap.widgets.BsButtonColumn',
						'template'=>'{view}'
					),
				),
			)); ?>
		<?php $this->endWidget(); ?>
	</div>
</div>