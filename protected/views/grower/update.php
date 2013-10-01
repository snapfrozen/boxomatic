<?php
$this->menu=array(
	array('label'=>'List Grower', 'url'=>array('index')),
	array('label'=>'Create Grower', 'url'=>array('create')),
	array('label'=>'View Grower', 'url'=>array('view', 'id'=>$model->grower_id)),
	array('label'=>'Manage Grower', 'url'=>array('admin')),
);
?>

<div class="row">
	<div class="large-12 columns">
		<h1>Update Grower</h1>
	</div>
	<div class="large-12 columns">
		<div class="panel">
			<a href="index.php?r=grower/index" class='button small'>List Grower</a>
			<a href="index.php?r=grower/create" class='button small'>Create Grower</a>
			<a href="index.php?r=grower/view&id=<?php echo $model->grower_id; ?>" class='button small'>View Grower</a>
			<a href="index.php?r=grower/admin" class='button small'>Manage Grower</a>
		</div>
	</div>
	<div class="large-12 columns">
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>

