<?php
/* @var $this MenuItemController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Menu Items',
);

$this->menu=array(
	array('label'=>'Create MenuItem', 'url'=>array('create')),
);
?>

<div class="page-header">
	<h1 class="text-muted">Menu Items</h1>
</div>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
