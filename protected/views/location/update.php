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
			<?php echo CHtml::link('List Locations',array('location/index'),array('class'=>'button small')) ?>
			<?php echo CHtml::link('Create Location',array('location/create'),array('class'=>'button small')) ?>
			<?php echo CHtml::link('View Location',array('location/view','id'=>$model->location_id),array('class'=>'button small')) ?>
			<?php echo CHtml::link('Maanage Locations',array('location/admin'),array('class'=>'button small')) ?>
		</div>
	</div>
	<div class="large-12 columns">
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>


