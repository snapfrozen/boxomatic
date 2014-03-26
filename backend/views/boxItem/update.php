<?php
$this->menu=array(
	array('label'=>'List BoxItem', 'url'=>array('index')),
	array('label'=>'Create BoxItem', 'url'=>array('create')),
	array('label'=>'View BoxItem', 'url'=>array('view', 'id'=>$model->box_item_id)),
	array('label'=>'Manage BoxItem', 'url'=>array('admin')),
);
?>

<h1>Update BoxItem <?php echo $model->box_item_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'SupplierProducts'=>$SupplierProducts)); ?>