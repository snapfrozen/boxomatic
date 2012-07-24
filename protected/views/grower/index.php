<?php
$this->breadcrumbs=array(
	'Growers',
);

$this->menu=array(
	array('label'=>'Create Grower', 'url'=>array('create')),
	array('label'=>'Manage Grower', 'url'=>array('admin')),
);
?>

<h1>Growers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
