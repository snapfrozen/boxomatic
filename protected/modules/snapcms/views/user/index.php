<?php
/* @var $this UserController */
/* @var $dataProvider CActiveDataProvider */
$user = Yii::app()->user;

$this->breadcrumbs=array(
	'Users',
);

$this->operations=array(
	array('label'=>'Create User', 'url'=>array('create'), 'visible'=>$user->checkAccess('Create User')),
);
?>

<div class="page-header">
	<h1 class="text-muted">Users</h1>
</div>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
	'htmlOptions'=>array('class'=>'list-view'),
	'cssFile'=>false,
)); ?>
