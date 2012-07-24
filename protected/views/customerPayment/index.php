<?php
$this->breadcrumbs=array(
	'Customer Payments',
);

$this->menu=array(
	array('label'=>'Create CustomerPayment', 'url'=>array('create')),
	array('label'=>'Manage CustomerPayment', 'url'=>array('admin')),
);
?>

<h1>Customer Payments</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
