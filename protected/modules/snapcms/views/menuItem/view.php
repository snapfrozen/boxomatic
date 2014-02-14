<?php
/* @var $this MenuItemController */
/* @var $model MenuItem */

$this->breadcrumbs=array(
	'Menus'=>array('/snapcms/menu/admin'),
	'View: ' . $model->Menu->name=>array('/snapcms/menu/view','id'=>$model->Menu->id),
	'View: ' . $model->title,
);

$this->operations=array(
	array('label'=>'Create MenuItem', 'url'=>array('create')),
	array('label'=>'Update MenuItem', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete MenuItem', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<div class="page-header">
	<h1 class="text-muted">View MenuItem #<?php echo $model->id; ?></h1>
</div>

<?php $this->widget('SnapDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'path',
		'title',
		'parent',
		'menu_id',
		'external_path',
		'created',
		'updated',
	),
)); ?>
