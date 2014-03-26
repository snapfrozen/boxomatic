<?php
$this->menu=array(
	array('label'=>'Create DeliveryDate', 'url'=>array('create')),
	array('label'=>'Manage DeliveryDate', 'url'=>array('admin')),
);
?>

<h1>DeliveryDates</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
