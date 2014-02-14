<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->full_name=>array('view','id'=>$model->id),
	'Update',
);

$this->operations=array(
	array('label'=>'Change Password', 'url'=>array('changePassword', 'id'=>$model->id)),
	array('label'=>'View User', 'url'=>array('view', 'id'=>$model->id)),
);
?>

<div class="page-header">
	<h1 class="text-muted">Update: <?php echo $model->full_name; ?></h1>
</div>

<?php $this->renderPartial('_form', array('model'=>$model,'groups'=>$groups,'userGroups'=>$userGroups)); ?>