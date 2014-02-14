<?php
/* @var $this MenuItemController */
/* @var $model MenuItem */

$this->breadcrumbs=array(
	'Menus'=>array('/snapcms/menu/admin'),
	'Update: ' . $model->Menu->name=>array('/snapcms/menu/update','id'=>$model->Menu->id),
	'Update: ' . $model->title,
);

$this->operations=array(
	array('label'=>'View Menu Item', 'url'=>array('view', 'id'=>$model->id)),
	array(
		'label'=>'Delete Menu Item', 
		'url'=>'#', 
		'linkOptions'=>array(
			'submit'=>array('delete','id'=>$model->id),
			'confirm'=>'Are you sure you want to delete this item?',
			'class'=>'text-danger',
		),
	),
);
?>

<div class="page-header">
	<h1 class="text-muted">Update Menu Item</h1>
</div>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>