<?php
$this->menu=array(
	array('label'=>'Create Box', 'url'=>array('create')),
	array('label'=>'Manage Box', 'url'=>array('admin')),
);
?>

<h1>Boxes</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
