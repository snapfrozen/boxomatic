<?php
/* @var $this SupplierPurchaseController */
/* @var $model SupplierPurchase */

$this->breadcrumbs=array(
	'Supplier Purchases'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SupplierPurchase', 'url'=>array('index')),
	array('label'=>'Manage SupplierPurchase', 'url'=>array('admin')),
);
?>
<div class="row">
	<div class="large-12 columns">

<h1>Create Order</h1>

	<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

	</div>
</div>