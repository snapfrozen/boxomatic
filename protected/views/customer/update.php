<?php
$this->menu=array(
	array('label'=>'List Customer', 'url'=>array('index')),
	array('label'=>'Create Customer', 'url'=>array('create')),
	array('label'=>'View Customer', 'url'=>array('view', 'id'=>$model->customer_id)),
	array('label'=>'Manage Customer', 'url'=>array('admin')),
);
?>

<h1>Update Customer <?php echo $model->customer_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>