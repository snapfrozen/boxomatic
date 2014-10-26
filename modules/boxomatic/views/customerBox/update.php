<?php
$this->menu=array(
	array('label'=>'List UserBox', 'url'=>array('index')),
	array('label'=>'Create UserBox', 'url'=>array('create')),
	array('label'=>'View UserBox', 'url'=>array('view', 'id'=>$model->user_box_id)),
	array('label'=>'Manage UserBox', 'url'=>array('admin')),
);
?>

<h1>Update Order</h1>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'Boxes'=>$Boxes,
	'items'=>$items,
	'SelectedBox'=>$SelectedBox)); 
?>