<?php
$this->breadcrumbs=array(
	'Boxes'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Box', 'url'=>array('index')),
	array('label'=>'Manage Box', 'url'=>array('admin')),
);
?>

<h1>Create Box</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>