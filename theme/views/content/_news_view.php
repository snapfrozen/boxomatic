<?php
/* @var $this ContentController */
/* @var $data Content */
//$image = isset($data->image);
?>
<div class="media">
	<?php echo SnapHtml::activeImage($data, 'image', 'medium', $data->title, true) ?>
	<div class="media-body">
		<h3 class="media-heading"><?php echo CHtml::link($data->title,array('content/view','id'=>$data->id)) ?></h3>
		<?php echo SnapHtml::editableArea($data, 'intro', $this->isEditable(), 'plain') ?>
	</div>
</div>