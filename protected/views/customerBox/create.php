<?php
$this->breadcrumbs=array(
	'Customer Boxes'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List CustomerBox', 'url'=>array('index')),
	array('label'=>'Manage CustomerBox', 'url'=>array('admin')),
);
?>

<h1>Create CustomerBox</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>