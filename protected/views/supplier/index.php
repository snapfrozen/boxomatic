<?php
$this->menu=array(
	array('label'=>'Create Supplier', 'url'=>array('create')),
	array('label'=>'Manage Supplier', 'url'=>array('admin')),
);
?>

<div class="row">
	<div class="large-12 columns">
		<h1>Suppliers</h1>
	</div>
	<?php $this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'_view',
	)); ?>
	
</div>

