<div class="view">
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('item_name')); ?>:</b>
	<?php echo CHtml::encode($data->item_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('grower_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->Grower->grower_name), array('grower/view', 'id'=>$data->Grower->grower_id)); ?>
	<br />

	<?php if(Yii::app()->user->checkAccess('admin')): ?>
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('item_value')); ?>:</b>
	<?php echo CHtml::encode($data->item_value); ?>
	<br />
	
	<?php endif; ?>

</div>