<?php
$this->menu=array(
	array('label'=>'Create BoxSize', 'url'=>array('create')),
	array('label'=>'Manage BoxSize', 'url'=>array('admin')),
);
?>

<h1>Box Sizes</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
