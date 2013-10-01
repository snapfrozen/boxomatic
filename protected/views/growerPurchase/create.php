<?php
/* @var $this GrowerPurchaseController */
/* @var $model GrowerPurchase */

$this->breadcrumbs=array(
	'Grower Purchases'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GrowerPurchase', 'url'=>array('index')),
	array('label'=>'Manage GrowerPurchase', 'url'=>array('admin')),
);
?>
<div class="row">
	<div class="large-12 columns">

<h1>Create Order</h1>

	<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

	</div>
</div>