<?php
/* @var $this ContentController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Contents',
);

$this->operations=array(
	array('label'=>'Create Page', 'url'=>array('/snapcms/contentType/index')),
);
?>

<div class="page-header">
	<h1 class="text-muted">Content</h1>
</div>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
