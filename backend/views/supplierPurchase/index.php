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
?>
<div class="row">
	<div class="large-12 columns">

<h1>Supplier Purchases</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

	</div>
</div>