<?php
$this->menu=array(
	array('label'=>'List CustomerPayment', 'url'=>array('index')),
	array('label'=>'Manage CustomerPayment', 'url'=>array('admin')),
);
?>

<h1>Create CustomerPayment</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>