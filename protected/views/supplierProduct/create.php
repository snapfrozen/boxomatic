<div class="row">
	<div class="large-12 columns">
		<h1>Create Supplier Product</h1>
	</div>
	<div class="large-12 columns">
		<div class="panel">
			<?php echo CHtml::link('Manage Supplier Products',array('supplierProduct/admin'),array('class'=>'button small')) ?>
		</div>
	</div>
	<div class="large-12 columns">
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>