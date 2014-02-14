<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Manage Groups',
);

$this->operations=array(
	//array('label'=>'Create Group', 'url'=>array('/snapcms/auth/createGroup')),
);
?>

<div class="page-header">
	<h1 class="text-muted">Manage Groups</h1>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$dataProvider,
	'cssFile' => false,
	'filter'=>new AuthItem,
	'columns'=>array(
		'name',
		'permissions',
		array(
			'class'=>'application.components.snap.SnapButtonColumn',
			'template'=>'{update}',
			'updateButtonUrl'=>"array('/snapcms/user/updateGroup','name'=>\$data->name)",
		),
	),
)); ?>