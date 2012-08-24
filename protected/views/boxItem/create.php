<?php
$this->menu=array(
	array('label'=>'List BoxItem', 'url'=>array('index')),
	array('label'=>'Manage BoxItem', 'url'=>array('admin')),
);
?>

<h1>Fill Boxes</h1>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'GrowerItems'=>$GrowerItems,
	'Weeks'=>$Weeks,
	'WeekBoxes'=>$WeekBoxes,
	'SelectedWeek'=>$SelectedWeek,
	'NewItem'=>$NewItem,
)); 
?>