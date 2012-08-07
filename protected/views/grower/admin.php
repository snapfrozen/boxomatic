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

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'grower-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'grower_id',
		'grower_name',
		'grower_mobile',
		'grower_phone',
		'grower_address',
		'grower_address2',
		/*
		'grower_suburb',
		'grower_state',
		'grower_postcode',
		'grower_distance_kms',
		'grower_bank_account_name',
		'grower_bank_bsb',
		'grower_bank_acc',
		'grower_email',
		'grower_website',
		'grower_certification_status',
		'grower_order_days',
		'grower_produce',
		'grower_notes',
		'grower_payment_details',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
