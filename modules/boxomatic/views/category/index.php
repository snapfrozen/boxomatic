<?php
/* @var $this CategoryController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->breadcrumbs=array(
	'Categories',
);

$this->menu=array(
array('icon' => 'glyphicon-plus-sign','label'=>'Create Category', 'url'=>array('create')),
array('icon' => 'glyphicon-plus-briefcase','label'=>'Manage Category', 'url'=>array('admin')),
);
?>
<?php echo BsHtml::pageHeader('Categories') ?>
<?php $this->widget('bootstrap.widgets.BsListView',array(
'dataProvider'=>$dataProvider,
'itemView'=>'_view',
)); ?>