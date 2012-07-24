<?php
$this->breadcrumbs=array(
	'Grower Items',
);

$this->menu=array(
	array('label'=>'Create Grower Item', 'url'=>array('create')),
	array('label'=>'Manage Grower Item', 'url'=>array('admin')),
);
?>

<h1>Grower Items</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
