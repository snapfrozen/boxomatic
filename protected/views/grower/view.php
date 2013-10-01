<div class="row">
	<div class="large-12 columns">
		<h1><?php echo $model->grower_name; ?></h1>
	</div>
	<div class="large-12 columns">
		<div class="panel">
			<?php echo CHtml::link('List',array('grower/index'), array('class' => 'button small')); ?>
			<?php echo CHtml::link('Create',array('grower/create'), array('class' => 'button small')); ?>
			<?php echo CHtml::link('Update',array('grower/update', 'id' => $model->grower_id), array('class' => 'button small')); ?>
			<?php echo CHtml::link('Delete',array('grower/delete', 'id' => $model->grower_id), array('class' => 'button small')); ?>
			<?php echo CHtml::link('Manage',array('grower/admin'), array('class' => 'button small')); ?>
		</div>
	</div>
	<div class="large-12 columns">
		<?php if(Yii::app()->user->checkAccess('admin')): ?>

		<?php $this->widget('zii.widgets.CDetailView', array(
			'data'=>$model,
			'cssFile' => '',
			'attributes'=>array(
				'grower_bank_account_name',
				'grower_bank_bsb',
				'grower_bank_acc',
				'grower_certification_status',
				'grower_order_days',
				'grower_produce',
				'grower_notes',
				'grower_payment_details',
			),
		)); ?>

		<?php endif; ?>
	</div>
</div>