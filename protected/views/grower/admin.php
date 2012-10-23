<?php
$this->menu=array(
	array('label'=>'List Grower', 'url'=>array('index')),
	array('label'=>'Create Grower', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('grower-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Growers</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
<?php $dataProvider=$model->search(); ?>
<?php $pageSize=Yii::app()->user->getState('pageSize',10); ?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'grower-grid',
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
		'grower_id',
		'grower_name',
		'grower_mobile',
		'grower_phone',
		'grower_address',
		'grower_address2',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
<?php Yii::app()->clientScript->registerScript('initPageSize',<<<EOD
    $('.change-pageSize').live('change', function() {
        $.fn.yiiGridView.update('grower-grid',{ data:{ pageSize: $(this).val() }})
    });
EOD
,CClientScript::POS_READY); ?>
