<div class="row">
	<div class="large-12 columns">
		<h1>Create User</h1>
	</div>
	<div class="large-12 columns">
		<div class="panel">
			<a href="index.php?r=user/index" class='button small'>List User</a>
			<a href="index.php?r=user/admin" class='button small'>Manage User</a>
		</div>
	</div>
	<div class="large-12 columns">
		<?php echo $this->renderPartial('_form', array('model'=>$model,'custLocDataProvider'=>$custLocDataProvider)); ?>
	</div>
</div>