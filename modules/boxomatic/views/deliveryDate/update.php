<?php
$this->breadcrumbs=array(
	'Box-O-Matic'=>array('/snapcms/boxomatic/index'),
	'Delivery Date'=>array('admin'),
	'Update',
);
$this->page_heading = 'Update';
$this->page_heading_subtext = 'Delivery Date';

$this->menu=array(
	array('icon' => 'glyphicon glyphicon-plus-sign', 'label'=>'Create Box Size', 'url'=>array('user/create')),
);
?>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
