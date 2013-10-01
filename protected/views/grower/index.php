<?php
$this->menu=array(
	array('label'=>'Create Grower', 'url'=>array('create')),
	array('label'=>'Manage Grower', 'url'=>array('admin')),
);
?>

<div class="row">
	<div class="large-12 columns">
		<h1>Growers</h1>
	</div>
	<?php $this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'_view',
	)); ?>
	
</div>

