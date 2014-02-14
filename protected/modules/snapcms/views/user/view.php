<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->full_name,
);

$this->operations=array(
	array('label'=>'Create User', 'url'=>array('create')),
	array('label'=>'Update User', 'url'=>array('update', 'id'=>$model->id)),
	array(
		'label'=>'Delete User', 
		'url'=>'#', 
		'linkOptions'=>array(
			'submit'=>array('delete','id'=>$model->id),
			'confirm'=>'Are you sure you want to delete this user?',
			'class'=>'text-danger',
		),
	),
);
?>

<div class="page-header">
	<h1 class="text-muted"><?php echo $model->full_name; ?></h1>
</div>

<?php $this->beginWidget('zii.widgets.CPortlet', array(
	'title'=>'Details',
	'titleCssClass' => 'panel-title',
	'decorationCssClass' => 'panel-heading',
	'htmlOptions'=>array('class'=>'panel panel-default')
)); ?>
<?php $this->widget('SnapDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'first_name',
		'last_name',
		'email',
		'user_groups',
	),
)); ?>
<?php $this->endWidget(); ?>
