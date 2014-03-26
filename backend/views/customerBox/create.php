<?php
$this->menu=array(
	array('label'=>'List UserBox', 'url'=>array('index')),
	array('label'=>'Manage UserBox', 'url'=>array('admin')),
);
?>

<h1>Order a Box</h1>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'Boxes'=>$Boxes,
	'items'=>$items,
	'SelectedBox'=>$SelectedBox)); 
?>