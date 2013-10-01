<div class="large-12 columns">
	<div class="panel">
		<ul class='no-bullet'>
			<li><?php echo CHtml::encode($data->getAttributeLabel('item_id')); ?> : <?php echo CHtml::link(CHtml::encode($data->item_id), array('view', 'id'=>$data->item_id)); ?></li>
			<li><?php echo CHtml::encode($data->getAttributeLabel('grower_name')); ?> : <?php echo CHtml::encode($data->Grower->grower_name); ?></li>
			<li><?php echo CHtml::encode($data->getAttributeLabel('item_name')); ?> : <?php echo CHtml::encode($data->item_name); ?></li>
			<li><?php echo CHtml::encode($data->getAttributeLabel('item_value')); ?> : $<?php echo CHtml::encode($data->item_value); ?></li>
			<li><?php echo CHtml::encode($data->getAttributeLabel('item_unit')); ?> : <?php echo CHtml::encode($data->item_unit); ?></li>
			<li><?php echo CHtml::encode($data->getAttributeLabel('item_available_from')); ?> : <?php echo CHtml::encode($data->item_available_from); ?></li>
			<li><?php echo CHtml::encode($data->getAttributeLabel('item_available_to')); ?> : <?php echo CHtml::encode($data->item_available_to); ?></li>
		</ul>
	</div>
</div>
