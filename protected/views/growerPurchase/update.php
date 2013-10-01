<?php
/* @var $this GrowerPurchaseController */
/* @var $model GrowerPurchase */

$this->breadcrumbs=array(
	'Grower Purchases'=>array('index'),
	$model->grower_purchases_id=>array('view','id'=>$model->grower_purchases_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GrowerPurchase', 'url'=>array('index')),
	array('label'=>'Create GrowerPurchase', 'url'=>array('create')),
	array('label'=>'View GrowerPurchase', 'url'=>array('view', 'id'=>$model->grower_purchases_id)),
	array('label'=>'Manage GrowerPurchase', 'url'=>array('admin')),
);
?>
<div class="row">
	<div class="large-12 columns">

<h1>Update GrowerPurchase <?php echo $model->grower_purchases_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

	</div>
</div>