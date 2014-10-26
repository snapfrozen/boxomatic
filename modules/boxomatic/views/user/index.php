<div class="row">
	<div class="large-12 columns">
		<h1>Users</h1>
	</div>
	<div class="large-12 columns">
		<div class="panel">
			<?php echo CHtml::link('Create User',array('user/create'),array('class'=>'button small')) ?>
			<?php echo CHtml::link('Manage User',array('user/admin'),array('class'=>'button small')) ?>
		</div>
	</div>
	
	<?php $dataProvider->pagination->pageSize = 9; ?>

	<?php $this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'_view'
	)); ?>
	
</div>

