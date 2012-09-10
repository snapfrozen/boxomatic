<h1>Fill Boxes</h1>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'GrowerItems'=>$GrowerItems,
	'Weeks'=>$Weeks,
	'WeekBoxes'=>$WeekBoxes,
	'SelectedWeek'=>$SelectedWeek,
	'selectedItemId'=>$selectedItemId,
)); 
?>