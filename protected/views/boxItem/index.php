<?php
$this->breadcrumbs=array(
	'Box Items',
);

$this->menu=array(
	array('label'=>'Create BoxItem', 'url'=>array('create')),
	array('label'=>'Manage BoxItem', 'url'=>array('admin')),
);
?>

<h1>Box Items</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
