<?php
    /* @var $this CategoryController */
    /* @var $Category Category */
?>

<?php
$this->breadcrumbs=array(
	'Categories'=>array('admin'),
	$Category->name=>array('view','id'=>$Category->id),
	'Update',
);

$this->menu=array(
	array('icon' => 'glyphicon-trash','label'=>'Delete Category', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$Category->id),'confirm'=>'Are you sure you want to delete this item?')),
);

$this->page_heading = 'Update';
$this->page_heading_subtext = $Category->name;

?>
<?php $this->renderPartial('_form', array('Category'=>$Category)); ?>