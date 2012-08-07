<?php
$this->menu=array(
	array('label'=>'List Week', 'url'=>array('index')),
	array('label'=>'Create Week', 'url'=>array('create')),
	array('label'=>'View Week', 'url'=>array('view', 'id'=>$model->week_id)),
	array('label'=>'Manage Week', 'url'=>array('admin')),
);
?>

<h1>Update Week <?php echo $model->week_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>