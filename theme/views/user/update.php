<?php
//$this->breadcrumbs=array(
//	'Box-O-Matic'=>array('/snapcms/boxomatic/index'),
//	'Customers'=>array('user/customers'),
//	$model->full_name,
//);
$this->page_heading = 'Update';
$this->page_heading_subtext = 'Customer';

$this->menu = array(
//	array('icon' => 'glyphicon-eye-open','label'=>'View Page', 'url'=>$this->createFrontendUrl("/content/view/",array("id"=>$Content->id))),	
	array('icon' => 'glyphicon-trash','label'=>'Delete Customer', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
)
		
?>
<?php echo $this->renderPartial('_form', array('model'=>$model,'custLocDataProvider'=>$custLocDataProvider)); ?>