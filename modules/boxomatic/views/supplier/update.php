<?php
$this->breadcrumbs=array(
	'Box-O-Matic'=>array('/snapcms/boxomatic/index'),
	'Suppliers'=>array('admin'),
	$model->name,
);
$this->page_heading = 'Update';
$this->page_heading_subtext = 'Supplier';

$this->menu = array(
	//array('icon' => 'glyphicon-trash','label'=>'Delete Content', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$Content->id),'confirm'=>'Are you sure you want to delete this item?')),
)
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
