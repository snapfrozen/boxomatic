<?php
$this->breadcrumbs=array(
	'Box-O-Matic'=>array('/snapcms/boxomatic/index'),
	'Box Size'=>array('admin'),
	'Create',
);
$this->page_heading = 'Create';
$this->page_heading_subtext = 'Box Size';

$this->menu = array(
//	array('icon' => 'glyphicon-eye-open','label'=>'View Page', 'url'=>$this->createFrontendUrl("/content/view/",array("id"=>$Content->id))),	
//	array('icon' => 'glyphicon-trash','label'=>'Delete Content', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$Content->id),'confirm'=>'Are you sure you want to delete this item?')),
)
?>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
