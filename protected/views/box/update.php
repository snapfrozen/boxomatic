<?php
$this->menu=array(
	array('label'=>'List Box', 'url'=>array('index')),
	array('label'=>'Create Box', 'url'=>array('create')),
	array('label'=>'View Box', 'url'=>array('view', 'id'=>$model->box_id)),
	array('label'=>'Manage Box', 'url'=>array('admin')),
);
?>

<h1>Update Box <?php echo $model->box_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>