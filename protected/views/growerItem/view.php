<div class="row">
	<div class="large-12 columns">
		<h1><?php echo $model->item_name; ?></h1>
	</div>

	<div class="large-12 columns">
		<div class="panel">
			<?php echo CHtml::link('List',array('growerItem/index'), array('class' => 'button small')); ?>
			<?php echo CHtml::link('Create',array('growerItem/create'), array('class' => 'button small')); ?>
			<?php echo CHtml::link('Update',array('growerItem/update', 'id' => $model->item_id), array('class' => 'button small')); ?>
			<?php echo CHtml::link('Delete',array('growerItem/delete','id' => $model->item_id), array('class' => 'button small')); ?>
			<?php echo CHtml::link('Manage',array('growerItem/admin'), array('class' => 'button small')); ?>
		</div>
	</div>
	
	<div class="large-12 columns">
		<?php $this->widget('zii.widgets.CDetailView', array(
			'data'=>$model,
			'cssFile' => '',
			'attributes'=>array(
				'Grower.grower_name',
				'item_name',
				'item_value',
				'item_unit',
				array( 'name'=>'item_available_from', 'value'=>Yii::app()->snapFormat->getMonthName($model->item_available_from) ),
				array( 'name'=>'item_available_to', 'value'=>Yii::app()->snapFormat->getMonthName($model->item_available_to) ),
			),
		)); ?>
	</div>
</div>