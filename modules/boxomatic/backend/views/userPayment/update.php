<?php
$this->menu=array(
	array('label'=>'List UserPayment', 'url'=>array('index')),
	array('label'=>'Create UserPayment', 'url'=>array('create')),
	array('label'=>'View UserPayment', 'url'=>array('view', 'id'=>$model->payment_id)),
	array('label'=>'Manage UserPayment', 'url'=>array('admin')),
);
?>

<h1>Update UserPayment <?php echo $model->payment_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>