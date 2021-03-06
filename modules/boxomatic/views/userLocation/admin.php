<?php
/* @var $this UserLocationController */
/* @var $model UserLocation */

$this->breadcrumbs=array(
	'Customer Locations'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List UserLocation', 'url'=>array('index')),
	array('label'=>'Create UserLocation', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('customer-location-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Customer Locations</h1>

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

<?php $this->widget('bootstrap.widgets.BsGridView', array(
	'id'=>'customer-location-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'customer_location_id',
		'user_id',
		'location_id',
		'address',
		'address2',
		'suburb',
		/*
		'state',
		'postcode',
		'phone',
		*/
		array(
			'class'=>'bootstrap.widgets.BsButtonColumn',
		),
	),
)); ?>
