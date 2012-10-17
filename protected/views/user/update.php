<h1><?php echo $model->full_name; ?></h1>
<p><?php echo CHtml::link('Change password', array('changePassword','id'=>$model->id)); ?></p>
<?php echo $this->renderPartial('_form', array('model'=>$model,'custLocDataProvider'=>$custLocDataProvider)); ?>