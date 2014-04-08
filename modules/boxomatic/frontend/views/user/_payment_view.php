<div class="panel">
	<ul class='no-bullet'>
		<li><?php echo CHtml::encode($data->getAttributeLabel('User.full_name')); ?>: <?php echo CHtml::encode($data->User->full_name); ?></li>
		<li><?php echo CHtml::encode($data->getAttributeLabel('payment_value')); ?>: <?php echo CHtml::encode($data->payment_value); ?></li>
		<li><?php echo CHtml::encode($data->getAttributeLabel('payment_type')); ?>: <?php echo CHtml::encode($data->payment_type); ?></li>
		<li><?php echo CHtml::encode($data->getAttributeLabel('payment_date')); ?>: <?php echo CHtml::encode($data->payment_date); ?></li>
		<li><?php echo CHtml::encode($data->getAttributeLabel('payment_note')); ?>: <?php echo CHtml::encode($data->payment_note); ?></li>
	</ul>
</div>