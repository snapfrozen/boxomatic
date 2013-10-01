<?php
$this->menu=array(
	array('label'=>'List Location', 'url'=>array('index')),
	array('label'=>'Manage Location', 'url'=>array('admin')),
);
?>

<div class="row">
	<div class="large-12 columns">
		<h1>Create Location</h1>
	</div>
	<div class="large-12 columns">
		<div class="panel">
			<?php echo CHtml::link('List Location', array('location/index'), array('class'=>'button small') ); ?>
			<?php echo CHtml::link('Manage Location', array('location/admin'), array('class'=>'button small') ); ?>
		</div>
	</div>
	<div class="large-12 columns">
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>


