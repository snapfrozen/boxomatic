<?php
/* @var $this MenuItemController */
/* @var $data MenuItem */
?>

<div class="media">
	
	<div class="row">
		<span class="col-md-2 control-label">	<?php echo CHtml::encode($data->getAttributeLabel('id')); ?>
</span>
		<span class="col-md-10">
			<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>		</span>
	</div>
	
	<div class="row">
		<span class="col-md-2 control-label">	<?php echo CHtml::encode($data->getAttributeLabel('path')); ?></span>
		<span class="col-md-10">
			<?php echo CHtml::encode($data->path); ?>		</span>
	</div>
		<div class="row">
		<span class="col-md-2 control-label">	<?php echo CHtml::encode($data->getAttributeLabel('title')); ?></span>
		<span class="col-md-10">
			<?php echo CHtml::encode($data->title); ?>		</span>
	</div>
		<div class="row">
		<span class="col-md-2 control-label">	<?php echo CHtml::encode($data->getAttributeLabel('parent')); ?></span>
		<span class="col-md-10">
			<?php echo CHtml::encode($data->parent); ?>		</span>
	</div>
		<div class="row">
		<span class="col-md-2 control-label">	<?php echo CHtml::encode($data->getAttributeLabel('menu_id')); ?></span>
		<span class="col-md-10">
			<?php echo CHtml::encode($data->menu_id); ?>		</span>
	</div>
		<div class="row">
		<span class="col-md-2 control-label">	<?php echo CHtml::encode($data->getAttributeLabel('external_path')); ?></span>
		<span class="col-md-10">
			<?php echo CHtml::encode($data->external_path); ?>		</span>
	</div>
		<div class="row">
		<span class="col-md-2 control-label">	<?php echo CHtml::encode($data->getAttributeLabel('created')); ?></span>
		<span class="col-md-10">
			<?php echo CHtml::encode($data->created); ?>		</span>
	</div>
		<?php /*
	<div class="row">
		<span class="col-md-2 control-label">	<?php echo CHtml::encode($data->getAttributeLabel('updated')); ?></span>
		<span class="col-md-10">
			<?php echo CHtml::encode($data->updated); ?>		</span>
	</div>
		*/ ?>

</div>