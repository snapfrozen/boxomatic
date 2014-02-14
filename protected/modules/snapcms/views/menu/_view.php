<?php
/* @var $this MenuController */
/* @var $data Menu */
?>

<div class="media">
	
	<div class="row">
		<span class="col-md-2 control-label">	<?php echo CHtml::encode($data->getAttributeLabel('id')); ?>
</span>
		<span class="col-md-10">
			<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>		</span>
	</div>
	
	<div class="row">
		<span class="col-md-2 control-label">	<?php echo CHtml::encode($data->getAttributeLabel('name')); ?></span>
		<span class="col-md-10">
			<?php echo CHtml::encode($data->name); ?>		</span>
	</div>
		<div class="row">
		<span class="col-md-2 control-label">	<?php echo CHtml::encode($data->getAttributeLabel('created')); ?></span>
		<span class="col-md-10">
			<?php echo CHtml::encode($data->created); ?>		</span>
	</div>
		<div class="row">
		<span class="col-md-2 control-label">	<?php echo CHtml::encode($data->getAttributeLabel('updated')); ?></span>
		<span class="col-md-10">
			<?php echo CHtml::encode($data->updated); ?>		</span>
	</div>
	
</div>