<?php
$this->breadcrumbs=array(
	'Customer Payments'=>array('index'),
	$model->payment_id=>array('view','id'=>$model->payment_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List CustomerPayment', 'url'=>array('index')),
	array('label'=>'Create CustomerPayment', 'url'=>array('create')),
	array('label'=>'View CustomerPayment', 'url'=>array('view', 'id'=>$model->payment_id)),
	array('label'=>'Manage CustomerPayment', 'url'=>array('admin')),
);
?>

<h1>Update CustomerPayment <?php echo $model->payment_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>