<?php
$this->menu=array(
	array('label'=>'List Week', 'url'=>array('index')),
	array('label'=>'Manage Week', 'url'=>array('admin')),
);
?>

<h1>Create Week</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>