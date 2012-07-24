<?php
$this->breadcrumbs=array(
	'Growers'=>array('index'),
	$model->grower_id=>array('view','id'=>$model->grower_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Grower', 'url'=>array('index')),
	array('label'=>'Create Grower', 'url'=>array('create')),
	array('label'=>'View Grower', 'url'=>array('view', 'id'=>$model->grower_id)),
	array('label'=>'Manage Grower', 'url'=>array('admin')),
);
?>

<h1>Update Grower <?php echo $model->grower_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>