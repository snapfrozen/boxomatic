<?php
$this->menu=array(
	array('label'=>'List Supplier', 'url'=>array('index')),
	array('label'=>'Create Supplier', 'url'=>array('create')),
	array('label'=>'View Supplier', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Supplier', 'url'=>array('admin')),
);
?>

<div class="row">
	<div class="large-12 columns">
		<h1>Update Supplier</h1>
	</div>
	<div class="large-12 columns">
		<div class="panel">
			<a href="index.php?r=supplier/index" class='button small'>List Supplier</a>
			<a href="index.php?r=supplier/create" class='button small'>Create Supplier</a>
			<a href="index.php?r=supplier/view&id=<?php echo $model->id; ?>" class='button small'>View Supplier</a>
			<a href="index.php?r=supplier/admin" class='button small'>Manage Supplier</a>
		</div>
	</div>
	<div class="large-12 columns">
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>

