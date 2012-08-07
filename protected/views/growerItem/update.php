<?php
$this->menu=array(
	array('label'=>'List GrowerItem', 'url'=>array('index')),
	array('label'=>'Create GrowerItem', 'url'=>array('create')),
	array('label'=>'View GrowerItem', 'url'=>array('view', 'id'=>$model->item_id)),
	array('label'=>'Manage GrowerItem', 'url'=>array('admin')),
);
?>

<h1>Update Grower Item <?php echo $model->item_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>