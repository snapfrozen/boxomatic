<?php
$this->breadcrumbs=array(
	'Box Sizes'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List BoxSize', 'url'=>array('index')),
	array('label'=>'Manage BoxSize', 'url'=>array('admin')),
);
?>

<h1>Create BoxSize</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>