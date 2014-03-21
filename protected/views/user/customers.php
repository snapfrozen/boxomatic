<?php
$this->menu=array(
	array('label'=>'List User', 'url'=>array('index')),
	array('label'=>'Create User', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('user-grid', {
		data: $(this).serialize()
	});
	return false;
});
");

Yii::app()->clientScript->registerScript('initPageSize',<<<EOD
	$('.change-pageSize').live('change', function() {
		$.fn.yiiGridView.update('user-grid',{ data:{ pageSize: $(this).val() }})
	});
EOD
,CClientScript::POS_READY);
?>

<div class="row">
	<div class="large-12 columns">
		<h1>Manage Customers</h1>
	</div>
	<div class="large-12 columns">
		<div class="panel">
			<?php echo CHtml::link('Create user',array('user/create'), array('class' => 'button small')) ?>
			<?php echo CHtml::link('Export',array('customer/export'), array('class' => 'button small')) ?>
			<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button button small')); ?>
		</div>
	</div>

	<div class="large-12 columns search-form" style="display:none">
		<?php $this->renderPartial('_search',array(
			'model'=>$model,
		)); ?>
	</div>

	<div class="large-12 columns">

		<?php $dataProvider=$model->search('customer'); ?>
		<?php $pageSize=Yii::app()->user->getState('pageSize',10); ?>
		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'user-grid',
			'cssFile' => '', 
			'dataProvider'=>$dataProvider,
			'summaryText'=>'Displaying {start}-{end} of {count} result(s). ' .
			CHtml::dropDownList(
				'pageSize',
				$pageSize,
				array(5=>5,10=>10,20=>20,50=>50,100=>100),
				array('class'=>'change-pageSize')) .
			' rows per page',
			'filter'=>$model,
			'columns'=>array(
				'id',
				'first_name',
				'last_name',
				'user_email',
				array(
					'name'=>'Customer.balance',
					'value'=>'$data->Customer ? Yii::app()->snapFormat->currency($data->Customer->balance) : ""',
				),
				//'last_login_time',
				//array( 'name'=>'customer_id', 'value'=>'empty($data->customer_id) ? "No" : "Yes"'),
				//array( 'name'=>'supplier_id', 'value'=>'empty($data->supplier_id) ? "No" : "Yes"'),
				array(
					'name'=>'tag_name_search',
					'filter'=>Tag::getUsedTags('customers'),
					'value'=>'CHtml::value($data,"Customer.tag_names")',
				),
				array(
					'class'=>'application.components.snap.SnapButtonColumn',
					'template'=>'{view}{update}{delete}{login}{reset_password}',
					'buttons'=>array(
						'login' => array
						(
							'label'=>'<i class="fi fi-key"></i>',
							'url'=> 'array("user/loginAs","id"=>$data->id)',
							'options'=>array('title'=>'Login As'),
						),
						'reset_password' => array
						(
							'label'=>'<i class="fi fi-lock"></i>',
							'url'=> 'array("user/resetPassword","id"=>$data->id)',
							'options'=>array(
								'confirm'=>'Are you sure you want to reset this user\'s password and send them a welcome email?',
								'title'=>'Reset Password',
							),
						),
					),
				),
			),
		)); ?>

	</div>

</div>
