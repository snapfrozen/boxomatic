<?php
$this->breadcrumbs=array(
	$model->User->full_name=>array('user/update','id'=>Yii::app()->request->getParam('id')),
	'Add a Location'
);
$this->page_heading = 'Add a Location';
?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="page-header">
            <h1>Add a Location</h1>
        </div>
        <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
    </div>
</div>