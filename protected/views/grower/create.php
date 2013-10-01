<?php
$this->menu=array(
	array('label'=>'List Grower', 'url'=>array('index')),
	array('label'=>'Manage Grower', 'url'=>array('admin')),
);
?>

<div class="row">
	<div class="large-12 columns">
		<h1>Create Grower</h1>
	</div>
	<div class="large-12 columns">
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>