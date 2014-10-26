<?php
$this->breadcrumbs=array(
	'Box-O-Matic'=>array('/snapcms/boxomatic/index'),
	'Customers',
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
	array('icon' => 'glyphicon glyphicon-plus-sign', 'label'=>'Create Customer', 'url'=>array('user/create')),
	array('icon' => 'glyphicon glyphicon-export', 'label'=>'Export Customers', 'url'=>array('user/export')),
);

Yii::app()->clientScript->registerScript('initPageSize',<<<EOD
	$('.change-pageSize').live('change', function() {
		$.fn.yiiGridView.update('user-grid',{ data:{ pageSize: $(this).val() }})
	});
EOD
,CClientScript::POS_READY);
?>
<?php
$this->beginWidget('bootstrap.widgets.BsPanel', array(
	'title'=>BsHtml::button('Advanced search',array(
		'data-toggle' => 'collapse',
		'data-target' => '#search-form',
		'class' =>'search-button', 
		'icon' => BsHtml::GLYPHICON_SEARCH,
		'color' => BsHtml::BUTTON_COLOR_PRIMARY), '#'),
)); ?>
	<div id="search-form" class="search-form collapse">
		<?php echo $this->renderPartial('_search',array(
			'model'=>$model,
		)); ?>
	</div><!-- search-form -->
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
				'name'=>'balance',
				'value'=>'SnapFormat::currency($data->balance)',
			),
			//'last_login_time',
			//array( 'name'=>'user_id', 'value'=>'empty($data->user_id) ? "No" : "Yes"'),
			//array( 'name'=>'supplier_id', 'value'=>'empty($data->supplier_id) ? "No" : "Yes"'),
			array(
				'name'=>'tag_name_search',
				'filter'=>Tag::getUsedTags('Users'),
				'value'=>'CHtml::value($data,"tag_names")',
			),
			array(
				'class'=>'bootstrap.widgets.BsButtonColumn',
				'template'=>'{view}{update}{delete}{login}{reset_password}',
				'buttons'=>array(
					'login' => array
					(
						'label'=>'<i class="glyphicon glyphicon-user"></i>',
						'url'=> 'array("user/loginAs","id"=>$data->id)',
						'options'=>array('title'=>'Login As'),
					),
					'reset_password' => array
					(
						'label'=>'<i class="glyphicon glyphicon-lock"></i>',
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
<?php $this->endWidget(); ?>