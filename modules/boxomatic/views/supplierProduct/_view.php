<div class="view large-12 columns end">
	<div class="image">
	<?php if(!empty($data->image)): ?>
		<?php echo CHtml::image($this->createUrl('supplierProduct/image',array('id'=>$data->id,'size'=>'tiny'))); ?>
	<?php else: ?>
		<?php echo CHtml::image($this->createUrl('supplierProduct/image',array('size'=>'tiny'))); ?>
	<?php endif; ?>
	</div>
	<div class="inner">
		<div class="row">
			<div class="large-9 columns">
				<h3><?php echo CHtml::encode($data->name); ?></h3>
				<span class="description"><?php echo $data->description; ?></span>
			</div>
		</div>
	</div>
</div>