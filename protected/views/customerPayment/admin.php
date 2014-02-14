<?php
$this->menu=array(
	array('label'=>'List CustomerPayment', 'url'=>array('index')),
	array('label'=>'Create CustomerPayment', 'url'=>array('create')),
);

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
");
?>

<h1>Manage Customer Payments</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'customer-payment-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'payment_type',
		'payment_value',
		'payment_date',
		'Customer.User.first_name',
		'Customer.User.last_name',
		array(
			'class'=>'application.components.snap.SnapButtonColumn',
		),
	),
)); ?>
