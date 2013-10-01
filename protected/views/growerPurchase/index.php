<?php
/* @var $this GrowerPurchaseController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Grower Purchases',
);

$this->menu=array(
	array('label'=>'Create GrowerPurchase', 'url'=>array('create')),
	array('label'=>'Manage GrowerPurchase', 'url'=>array('admin')),
);
?>
<div class="row">
	<div class="large-12 columns">

<h1>Grower Purchases</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

	</div>
</div>