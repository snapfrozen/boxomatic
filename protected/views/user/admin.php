<?php

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
		<h1>Manage Users</h1>
	</div>

	<div class="large-12 columns">
		<div class="panel">
			<a href="index.php?r=user/index" class="button small">List User</a>
			<a href="index.php?r=user/create" class="button small">Create User</a>
			<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button button small')); ?>
		</div>
	</div>

	<div class="large 12 columns search-form" style='display:none'>
		<?php $this->renderPartial('_search',array(
			'model'=>$model,
		)); ?>
	</div>

	<div class="large-12 columns">
		
		<?php $dataProvider=$model->search(); ?>
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
				'last_login_time',
				array( 'name'=>'customer_id', 'value'=>'empty($data->customer_id) ? "No" : "Yes"'),
				array( 'name'=>'grower_id', 'value'=>'empty($data->grower_id) ? "No" : "Yes"'),
				array(
					'class'=>'CButtonColumn',
					'template'=>'{view}{update}{delete}{login}',
					'buttons'=>array(
						'login' => array
						(
							'url'=> 'array("user/loginAs","id"=>$data->id)',
							'options'=>array('class'=>'text'),
						),
					),
				),
			),
		)); ?>
	</div>
</div>
