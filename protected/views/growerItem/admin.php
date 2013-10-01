<?php

/*$this->menu=array(
	array('label'=>'Create Inventory', 'url'=>array('create')),
);*/

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('grower-item-grid', {
		data: $(this).serialize()
	});
	return false;
});
");

Yii::app()->clientScript->registerScript('initPageSize',<<<EOD
	$('.change-pageSize').live('change', function() {
		$.fn.yiiGridView.update('grower-item-grid',{ data:{ pageSize: $(this).val() }})
	});
EOD
,CClientScript::POS_READY);

?>

<div class="row">
	<div class="large-12 columns">
		<h1>Manage Inventory</h1>
			<p>
			You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
			or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
			</p>
	</div>

	<div class="large-12 columns">
		<div class="panel">
			<a href="index.php?r=growerItem/create" class="button small">Create Inventory</a>
			<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button button small')); ?>
		</div>
	</div>

	<div class="large-12 columns">
		<div class="search-form" style="display:none">
		<?php $this->renderPartial('_search',array(
			'model'=>$model,
		)); ?>
		</div>
	</div>

	<div class="large-12 columns">
		<?php $dataProvider=$model->search(); ?>
		<?php $pageSize=Yii::app()->user->getState('pageSize',10); ?>
		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'grower-item-grid',
			'cssFile' => '', 
			'dataProvider'=>$dataProvider,
			'filter'=>$model,
			'summaryText'=>'Displaying {start}-{end} of {count} result(s). ' .
			CHtml::dropDownList(
				'pageSize',
				$pageSize,
				array(5=>5,10=>10,20=>20,50=>50,100=>100),
				array('class'=>'change-pageSize')) .
			' rows per page',
			'columns'=>array(
		        array( 
					'name'=>'grower_search', 
					'value'=>'$data->Grower->grower_name',
					'visible'=>Yii::app()->user->checkAccess('admin'),
				),
				'item_name',
				'item_value',
				'item_unit',
				array( 'name'=>'item_available_from', 'value'=>'Yii::app()->snapFormat->getMonthName($data->item_available_from)' ),
				array( 'name'=>'item_available_to', 'value'=>'Yii::app()->snapFormat->getMonthName($data->item_available_to)' ),
				/*
				'item_available_to',
				*/
				array(
					'class'=>'CButtonColumn',
				),
			),
		)); ?>
	</div>


</div>
