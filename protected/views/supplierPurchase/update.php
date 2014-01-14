<?php
/* @var $this SupplierPurchaseController */
/* @var $model SupplierPurchase */

$this->breadcrumbs=array(
	'Supplier Purchases'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SupplierPurchase', 'url'=>array('index')),
	array('label'=>'Create SupplierPurchase', 'url'=>array('create')),
	array('label'=>'View SupplierPurchase', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage SupplierPurchase', 'url'=>array('admin')),
);
?>
<div class="row">
	<div class="large-12 columns">

<h1>Update SupplierPurchase <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

	</div>
</div>