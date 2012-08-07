<?php
$this->menu=array(
	array('label'=>'List CustomerBox', 'url'=>array('index')),
	array('label'=>'Manage CustomerBox', 'url'=>array('admin')),
);
?>

<h1>Order a Box</h1>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'Boxes'=>$Boxes,
	'items'=>$items,
	'SelectedBox'=>$SelectedBox)); 
?>