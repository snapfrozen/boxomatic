<?php
/* @var $this ContentController */
/* @var $data Content */
?>

<div class="media">
	
	<div class="row">
		<span class="col-md-2 control-label">	<?php echo CHtml::encode($data->getAttributeLabel('id')); ?>
</span>
		<span class="col-md-10">
			<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>		</span>
	</div>
	
	<div class="row">
		<span class="col-md-2 control-label">	<?php echo CHtml::encode($data->getAttributeLabel('title')); ?></span>
		<span class="col-md-10">
			<?php echo CHtml::encode($data->title); ?>		</span>
	</div>
		<div class="row">
		<span class="col-md-2 control-label">	<?php echo CHtml::encode($data->getAttributeLabel('type')); ?></span>
		<span class="col-md-10">
			<?php echo CHtml::encode($data->type); ?>		</span>
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