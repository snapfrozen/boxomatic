<?php
$this->menu=array(
	array('label'=>'List Location', 'url'=>array('index')),
	array('label'=>'Create Location', 'url'=>array('create')),
	array('label'=>'Update Location', 'url'=>array('update', 'id'=>$model->location_id)),
	array('label'=>'Delete Location', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->location_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Location', 'url'=>array('admin')),
);
?>
<div class="row">
	<div class="large-12 columns">
		<h1>View Location</h1>
	</div>
	<div class="large-12 columns">
		<?php $this->widget('zii.widgets.CDetailView', array(
			'data'=>$model,
			'cssFile' => '', 
			'attributes'=>array(
				'location_id',
				'location_name',
				'location_delivery_value',
			),
		)); ?>
	</div>
</div>



