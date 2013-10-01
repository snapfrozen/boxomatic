<div class="row">
	<div class="large-12 columns">
		<h1>Create BoxSize</h1>
	</div>
	
	<div class="large-12 columns">
		<div class="panel">
			<?php echo CHtml::link('List BoxSize',array('boxSize/index'), array('class' => 'button small')) ?>
			<?php echo CHtml::link('Manage BoxSize',array('boxSize/admin'), array('class' => 'button small')) ?>
		</div>
	</div>

	<div class="large-12 columns">
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>

