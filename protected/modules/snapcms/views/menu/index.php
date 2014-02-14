<?php
/* @var $this MenuController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Menu List',
);

$this->operations=array(
	array('label'=>'Create Menu', 'url'=>array('create')),
);
?>

<div class="page-header">
	<h1 class="text-muted">Menu List</h1>
</div>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
