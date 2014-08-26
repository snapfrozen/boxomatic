<div class="row">
	<div class="large-12 columns">
		<h1><?php echo $model->name; ?></h1>
	</div>

	<div class="large-12 columns">
		<div class="panel">
			<?php echo CHtml::link('List',array('supplierProduct/index'), array('class' => 'button small')); ?>
			<?php echo CHtml::link('Create',array('supplierProduct/create'), array('class' => 'button small')); ?>
			<?php echo CHtml::link('Update',array('supplierProduct/update', 'id' => $model->id), array('class' => 'button small')); ?>
			<?php echo CHtml::link('Delete',array('supplierProduct/delete','id' => $model->id), array('class' => 'button small')); ?>
			<?php echo CHtml::link('Manage',array('supplierProduct/admin'), array('class' => 'button small')); ?>
		</div>
	</div>
	
	<div class="large-12 columns">
		<?php $this->widget('zii.widgets.CDetailView', array(
			'data'=>$model,
			'cssFile' => '',
			'attributes'=>array(
				'Supplier.name',
				'name',
				'value',
				'unit',
				array( 'name'=>'available_from', 'value'=>SnapFormat::getMonthName($model->available_from) ),
				array( 'name'=>'available_to', 'value'=>SnapFormat::getMonthName($model->available_to) ),
			),
		)); ?>
	</div>
</div>