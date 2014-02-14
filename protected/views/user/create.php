<div class="row">
	<div class="large-12 columns">
		<h1>Create User</h1>
	</div>
	<div class="large-12 columns">
		<div class="panel">
			<?php echo CHtml::link('Manage User',array('user/admin'),array('class'=>'button small')) ?>
		</div>
	</div>
	<div class="large-12 columns">
		<?php echo $this->renderPartial('_form', array('model'=>$model,'custLocDataProvider'=>$custLocDataProvider)); ?>
	</div>
</div>