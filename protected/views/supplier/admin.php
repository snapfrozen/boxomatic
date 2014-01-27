<?php
/*
$this->menu=array(
	array('label'=>'List Supplier', 'url'=>array('index')),
	array('label'=>'Create Supplier', 'url'=>array('create')),
);*/

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('supplier-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<div class="row">
	<div class="large-12 columns">
		<h1>Manage Suppliers</h1>
		<p>
			You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
			or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
		</p>
		
	</div>

	<div class="large-12 columns">
		<div class="panel">
			<?php echo CHtml::link('List Suppliers','index.php?r=supplier/index',array('class'=>'button small')); ?>
			<?php echo CHtml::link('Create','index.php?r=supplier/create',array('class'=>'button small')); ?>
			<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button button small')); ?>
		</div>
	</div>

	<div class="large-12 columns search-form" style="display:none">
		<?php $this->renderPartial('_search',array(
			'model'=>$model,
		)); ?>
	</div>

	<div class="large-12 columns">
		<?php $dataProvider=$model->search(); ?>
		<?php $pageSize=Yii::app()->user->getState('pageSize',10); ?>
		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'supplier-grid',
			'dataProvider'=>$dataProvider,
			'cssFile' => '', 
			'filter'=>$model,
			'summaryText'=>'Displaying {start}-{end} of {count} result(s). ' .
				CHtml::dropDownList(
					'pageSize',
					$pageSize,
					array(5=>5,10=>10,20=>20,50=>50,100=>100),
					array('class'=>'change-pageSize')) .
				' rows per page',
			'columns'=>array(
				'id',
				'name',
				'mobile',
				'phone',
				'address',
				'address2',
				array(
					'class'=>'SnapButtonColumn',
				),
			),
		)); ?>
	</div>
</div>

<?php Yii::app()->clientScript->registerScript('initPageSize',<<<EOD
    $('.change-pageSize').live('change', function() {
        $.fn.yiiGridView.update('supplier-grid',{ data:{ pageSize: $(this).val() }})
    });
EOD
,CClientScript::POS_READY); ?>
