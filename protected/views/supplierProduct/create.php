<div class="row">
	<div class="large-12 columns">
		<h1>Create SupplierProduct</h1>
	</div>
	<div class="large-12 columns">
		<div class="panel">
			<a href="index.php?r=supplierProduct/index" class='button small'>List SupplierProduct</a>
			<a href="index.php?r=supplierProduct/admin" class='button small'>Manage SupplierProduct</a>
		</div>
	</div>
	<div class="large-12 columns">
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>