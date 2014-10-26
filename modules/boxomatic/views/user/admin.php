<?php
$this->breadcrumbs=array(
	'Box-O-Matic'=>array('/snapcms/boxomatic/index'),
	'Delivery Dates',
);
$this->page_heading = 'Customers';

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('user-grid', {
		data: $(this).serialize()
	});
	return false;
});
");

$this->menu = array(
	//array('icon' => 'glyphicon-plus-sign','label'=>'Create Customer', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$Content->id),'confirm'=>'Are you sure you want to delete this item?')),
);

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
			<?php echo CHtml::link('Create User',array('user/create'),array('class'=>'button small')) ?>
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
		<?php $this->widget('bootstrap.widgets.BsGridView', array(
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
				'email',
				array(
					'name'=>'Customer.balance',
					'value'=>'$data->Customer ? SnapFormat::currency($data->Customer->balance) : ""',
				),
				'last_login_time',
				array( 'name'=>'user_id', 'value'=>'empty($data->user_id) ? "No" : "Yes"'),
				array( 'name'=>'supplier_id', 'value'=>'empty($data->supplier_id) ? "No" : "Yes"'),
				array(
					'class'=>'bootstrap.widgets.BsButtonColumn',
					'template'=>'{view}{update}{delete}{login}',
					'buttons'=>array(
						'login' => array
						(
							'label'=>'<i class="fi fi-key"></i>',
							'url'=> 'array("user/loginAs","id"=>$data->id)',
							'options'=>array('title'=>'Login As'),
						),
					),
				),
			),
		)); ?>
	</div>
</div>
