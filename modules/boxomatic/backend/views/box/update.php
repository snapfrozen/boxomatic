<?php
$this->breadcrumbs=array(
	'Box-O-Matic'=>array('/snapcms/boxomatic/index'),
	'Boxes'=>array('boxes/index'),
	'Box Packing'=>array('boxItem/create','date'=>$model->delivery_date_id),
	'Edit Name'
);
$this->page_heading = 'Edit Name';
//$this->page_heading_subtext = 'Box Size';
$this->menu = array(
	//array('icon' => 'glyphicon-trash','label'=>'Delete Content', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$Content->id),'confirm'=>'Are you sure you want to delete this item?')),
)
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>