<?php
/* @var $this SupplierPurchaseController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Supplier Purchases',
);

$this->menu=array(
	array('label'=>'Create SupplierPurchase', 'url'=>array('create')),
	array('label'=>'Manage SupplierPurchase', 'url'=>array('admin')),
);

$this->page_heading = 'Supplier Purchases';
$this->page_heading_subtext = 'Order';

?>

<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_view',
)); ?>