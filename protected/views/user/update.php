<h1><?php echo $model->full_name; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'custLocDataProvider'=>$custLocDataProvider)); ?>