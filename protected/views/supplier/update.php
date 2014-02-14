<?php
$this->menu=array(
	array('label'=>'List Supplier', 'url'=>array('index')),
	array('label'=>'Create Supplier', 'url'=>array('create')),
	array('label'=>'View Supplier', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Supplier', 'url'=>array('admin')),
);
?>

<div class="row">
	<div class="large-12 columns">
		<h1>Update Supplier</h1>
	</div>
	<div class="large-12 columns">
		<div class="panel">
			<?php echo CHtml::link('List Supplier',array('supplier/index'),array('class'=>'button small')) ?>
			<?php echo CHtml::link('Create Supplier',array('supplier/create'),array('class'=>'button small')) ?>
			<?php echo CHtml::link('Manage Supplier',array('supplier/admin'),array('class'=>'button small')) ?>
		</div>
	</div>
	<div class="large-12 columns">
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>

