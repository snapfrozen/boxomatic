<?php
/*$this->menu=array(
	array('label'=>'List Location', 'url'=>array('index')),
	array('label'=>'Create Location', 'url'=>array('create')),
	array('label'=>'View Location', 'url'=>array('view', 'id'=>$model->location_id)),
	array('label'=>'Manage Location', 'url'=>array('admin')),
);*/
?>

<div class="row">
	<div class="large-12 columns">
		<h1>Manage Location</h1>
	</div>
	<div class="large-12 columns">
		<div class="panel">
			<a href="index.php?r=location/index" class="button small">List Locations</a>
			<a href="index.php?r=location/create" class="button small">Create Location</a>
			<a href="index.php?r=location/view&id=<?php echo $model->location_id; ?>" class="button small">View Location</a>
			<a href="index.php?r=location/admin" class="button small">Maanage Locations</a>
		</div>
	</div>
	<div class="large-12 columns">
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>


