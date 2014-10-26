<?php
$this->breadcrumbs=array(
	'Box-O-Matic'=>array('/snapcms/boxomatic/index'),
	'Customers'=>array('user/customers'),
	'Locations'=>array('location/admin'),
	$model->location_name,
);
$this->page_heading = 'Update';
$this->page_heading_subtext = 'Location';
?>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

