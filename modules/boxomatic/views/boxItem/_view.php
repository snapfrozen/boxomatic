<div class="view">
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('item_name')); ?>:</b>
	<?php echo CHtml::encode($data->item_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('supplier_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->Supplier->name), array('supplier/view', 'id'=>$data->Supplier->id)); ?>
	<br />

	<?php if(Yii::app()->user->checkAccess('Admin')): ?>
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('item_value')); ?>:</b>
	<?php echo CHtml::encode($data->item_value); ?>
	<br />
	
	<?php endif; ?>

</div>