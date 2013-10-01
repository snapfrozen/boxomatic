<div class="row">
	<div class="large-12 columns">
		<h1>Users</h1>
	</div>
	<div class="large-12 columns">
		<div class="panel">
			<a href="index.php?r=user/create" class="button small">Create User</a>
			<a href="index.php?r=user/admin" class="button small">Manage User</a>
		</div>
	</div>
	
	<?php $dataProvider->pagination->pageSize = 9; ?>

	<?php $this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'_view'
	)); ?>
	
</div>

