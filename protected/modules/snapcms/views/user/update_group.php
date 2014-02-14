<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Manage Groups'=>array('/snapcms/user/groups'),
	$name,
);

$this->operations=array(
);
?>

<div class="page-header">
	<h1 class="text-muted">Update Group: <?php echo $name; ?></h1>
</div>

<div class="form">
	<?php $form=$this->beginWidget('SnapActiveForm', array(
		'id'=>'update-group-form',
		'enableAjaxValidation'=>false,
		'htmlOptions'=>array('class'=>'col-md-12'),
	)); ?>

	<div id="groups" class="tab-pane">
		<div class="form-group">
			<?php echo CHtml::checkBoxList(
				'GroupPermissions', 
				CHtml::listData($groupPermissions,'name','name'), 
				CHtml::listData($permissions,'name','name'),
				array('disabled'=>$name == 'Admin')); ?>
		</div>
	</div>
	
	<?php foreach($tasks as $task): ?>
		<p><?php echo $task->name ?></p>
	<?php endforeach; ?>
	
	<div class="buttons">
		<?php echo CHtml::submitButton('Save', array('class'=>'btn btn-primary btn-lg')); ?>
	</div>

	<?php $this->endWidget(); ?>
</div>