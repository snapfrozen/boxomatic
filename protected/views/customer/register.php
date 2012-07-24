<?php
$this->breadcrumbs=array(
	'Customers'=>array('index'),
	'Register',
);

/*
$this->menu=array(
	array('label'=>'List Customer', 'url'=>array('index')),
	array('label'=>'Manage Customer', 'url'=>array('admin')),
);
*/
?>

<h1>Register Customer</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>