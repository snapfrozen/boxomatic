<div class="large-12 columns">
	<div class="panel">
		<ul class='no-bullet'>
			<li><?php echo CHtml::encode($data->getAttributeLabel('id')); ?> : <?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?></li>
			<li><?php echo CHtml::encode($data->getAttributeLabel('name')); ?> : <?php echo CHtml::encode($data->Supplier->name); ?></li>
			<li><?php echo CHtml::encode($data->getAttributeLabel('name')); ?> : <?php echo CHtml::encode($data->name); ?></li>
			<li><?php echo CHtml::encode($data->getAttributeLabel('value')); ?> : $<?php echo CHtml::encode($data->value); ?></li>
			<li><?php echo CHtml::encode($data->getAttributeLabel('unit')); ?> : <?php echo CHtml::encode($data->unit); ?></li>
			<li><?php echo CHtml::encode($data->getAttributeLabel('available_from')); ?> : <?php echo CHtml::encode($data->available_from); ?></li>
			<li><?php echo CHtml::encode($data->getAttributeLabel('available_to')); ?> : <?php echo CHtml::encode($data->available_to); ?></li>
		</ul>
	</div>
</div>
