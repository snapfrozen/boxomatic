<?php
$this->menu=array(
	array('label'=>'List Location', 'url'=>array('index')),
	array('label'=>'Create Location', 'url'=>array('create')),
	array('label'=>'View Location', 'url'=>array('view', 'id'=>$model->location_id)),
	array('label'=>'Manage Location', 'url'=>array('admin')),
);
?>

<h1>Update Location <?php echo $model->location_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>