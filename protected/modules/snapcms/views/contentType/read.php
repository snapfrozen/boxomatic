<?php use yii\helpers\Html; ?>
<div class="row-fluid">
	<div class="pull-right btn-group">
		<?php echo Html::a('Update', array('content/update', 'id' => $model->id), array('class' => 'btn btn-primary')); ?>
		<?php echo Html::a('Delete', array('content/delete', 'id' => $model->id), array('class' => 'btn btn-danger')); ?>
	</div>

	<h1><?php echo Html::encode($model->name); ?></h1>
</div>