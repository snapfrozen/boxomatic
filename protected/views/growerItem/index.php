<div class="row">
	<div class="large-12 columns">
		<h1>Grower Items</h1>
	</div>
	
	<div class="large-12 columns">
		<div class="panel">
			<?php echo CHtml::link('Create',array('growerItem/create'), array('class' => 'button small')); ?>
			<?php echo CHtml::link('Manage',array('growerItem/admin'), array('class' => 'button small')); ?>
		</div>
	</div>

	<?php $this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider, 
		'itemView'=>'_view',
	)); ?>
	
</div>



