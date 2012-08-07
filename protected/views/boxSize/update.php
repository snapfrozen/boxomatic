<?php
$this->menu=array(
	array('label'=>'List BoxSize', 'url'=>array('index')),
	array('label'=>'Create BoxSize', 'url'=>array('create')),
	array('label'=>'View BoxSize', 'url'=>array('view', 'id'=>$model->box_sizes)),
	array('label'=>'Manage BoxSize', 'url'=>array('admin')),
);
?>

<h1>Update BoxSize <?php echo $model->box_sizes; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>