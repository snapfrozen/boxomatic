<?php
/* @var $this PackingStationController */
/* @var $model PackingStation */

$this->breadcrumbs=array(
	'Box-O-Matic'=>array('/snapcms/boxomatic/index'),
	'Packing Stations'=>array('admin'),
	'Update',
);

$this->page_heading = 'Update';
$this->page_heading_subtext = 'Packing Station';
?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>