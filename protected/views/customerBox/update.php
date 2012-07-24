<?php
$this->breadcrumbs=array(
	'Customer Boxes'=>array('index'),
	$model->customer_box_id=>array('view','id'=>$model->customer_box_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List CustomerBox', 'url'=>array('index')),
	array('label'=>'Create CustomerBox', 'url'=>array('create')),
	array('label'=>'View CustomerBox', 'url'=>array('view', 'id'=>$model->customer_box_id)),
	array('label'=>'Manage CustomerBox', 'url'=>array('admin')),
);
?>

<h1>Update CustomerBox <?php echo $model->customer_box_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>