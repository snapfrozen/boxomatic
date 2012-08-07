<?php
$this->menu=array(
	array('label'=>'List GrowerItem', 'url'=>array('index')),
	array('label'=>'Manage GrowerItem', 'url'=>array('admin')),
);
?>

<h1>Create GrowerItem</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>