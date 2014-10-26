<?php
$this->breadcrumbs=array(
	$model->User->full_name=>array('user/update','id'=>$model->user_id),
	'Add a Location'
);
$this->page_heading = 'Update Location';
?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="page-header">
            <h1>Update Location</h1>
        </div>
        <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
    </div>
</div>