<?php
$this->menu=array(
	array('label'=>'Create UserBox', 'url'=>array('create')),
	array('label'=>'Manage UserBox', 'url'=>array('admin')),
);
?>

<h1>Customer Boxes</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
