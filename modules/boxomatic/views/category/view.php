<?php
/* @var $this CategoryController */
/* @var $Category Category */
?>

<?php
$this->breadcrumbs=array(
	'Categories'=>array('admin'),
	$Category->name,
);

$this->menu=array(
array('icon' => 'glyphicon-pencil','label'=>'Update Category', 'url'=>array('update', 'id'=>$Category->id)),
array('icon' => 'glyphicon-trash','label'=>'Delete Category', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$Category->id),'confirm'=>'Are you sure you want to delete this item?')),
);

$this->page_heading = 'View';
$this->page_heading_subtext = $Category->name;

?>
<?php $this->widget('application.widgets.SnapDetailView',array(
'htmlOptions' => array(
'class' => 'table table-striped table-condensed table-hover',
),
'data'=>$Category,
'attributes'=>array(
		'id',
		'parent:number',
		'name',
		'description',
),
)); ?>