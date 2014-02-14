<?php
/* @var $this ContentController */
/* @var $model Content */

$this->breadcrumbs=array(
	'Pages'=>array('admin'),
	//$model->title=>array('view','id'=>$model->id),
	'Update: ' . $model->title,
);

$this->operations=array(
	array('label'=>'Create Page', 'url'=>array('/snapcms/contentType/index')),
	array('label'=>'View Page', 'url'=>array('/content/view', 'id'=>$model->id)),
);
?>

<div class="page-header">
	<h1 class="text-muted">Update Page</h1>
</div>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>