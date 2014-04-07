<div class="row">
	<div class="large-12 columns">
		<h1><?php echo $model->name; ?></h1>
	</div>
	<div class="large-12 columns">
		<div class="panel">
			<?php echo CHtml::link('Create',array('supplier/create'), array('class' => 'button small')); ?>
			<?php echo CHtml::link('Update',array('supplier/update', 'id' => $model->id), array('class' => 'button small')); ?>
			<?php echo CHtml::link('Delete',array('supplier/delete', 'id' => $model->id), array('class' => 'button small')); ?>
			<?php echo CHtml::link('Manage',array('supplier/admin'), array('class' => 'button small')); ?>
		</div>
	</div>
	<div class="large-12 columns">
		<?php if(Yii::app()->user->checkAccess('Admin')): ?>

		<?php $this->widget('zii.widgets.CDetailView', array(
			'data'=>$model,
			'cssFile' => '',
			'attributes'=>array(
				'bank_account_name',
				'bank_bsb',
				'bank_acc',
				'certification_status',
				'order_days',
				'produce',
				'notes',
				'payment_details',
			),
		)); ?>

		<?php endif; ?>
	</div>
</div>