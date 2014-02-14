<?php
/* @var $this UserController */
/* @var $data User */
?>

<div class="media">

	<div class="row">
		<span class="col-md-2 control-label"><?php echo CHtml::encode($data->getAttributeLabel('full_name')); ?></span>
		<span class="col-md-10">
		<?php echo CHtml::link(CHtml::encode($data->full_name), array('view', 'id'=>$data->id)); ?>
		</span>
	</div>

	<div class="row">
		<span class="col-md-2 control-label"><?php echo CHtml::encode($data->getAttributeLabel('email')); ?></span>
		<span class="col-md-10">
		<?php echo CHtml::encode($data->email); ?>
		</span>
	</div>

</div>