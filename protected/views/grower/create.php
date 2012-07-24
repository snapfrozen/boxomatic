<?php
$this->breadcrumbs=array(
	'Growers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Grower', 'url'=>array('index')),
	array('label'=>'Manage Grower', 'url'=>array('admin')),
);
?>

<h1>Create Grower</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>