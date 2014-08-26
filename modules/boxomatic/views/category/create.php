<?php
    /* @var $this CategoryController */
    /* @var $Category Category */
$this->breadcrumbs=array(
	'Box-O-Matic'=>array('/snapcms/boxomatic/index'),
	'Suppliers'=>array('supplier/admin'),
	'Supplier Products'=>array('supplierProduct/admin'),
	'Categories'=>array('category/admin'),
	'Create',
);

$this->page_heading = 'Create';
$this->page_heading_subtext = 'Category';
?>
<?php $this->renderPartial('_form', array('Category'=>$Category)); ?>