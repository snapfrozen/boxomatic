<div class="row">
	<div class="large-12 columns">
		<h1>Fill Boxes</h1>
	</div>
	<?php echo $this->renderPartial('_form', array(
			'model'=>$model,
			'GrowerItems'=>$GrowerItems,
			'Weeks'=>$Weeks,
			'WeekBoxes'=>$WeekBoxes,
			'SelectedWeek'=>$SelectedWeek,
			'selectedItemId'=>$selectedItemId,
		)); 
	?>
</div>

