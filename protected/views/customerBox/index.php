<?php
$this->breadcrumbs=array(
	'Customer Boxes',
);

$this->menu=array(
	array('label'=>'Create CustomerBox', 'url'=>array('create')),
	array('label'=>'Manage CustomerBox', 'url'=>array('admin')),
);
?>

<h1>Customer Boxes</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
