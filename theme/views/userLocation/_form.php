<?php
/* @var $this CustomerLocationController */
/* @var $model CustomerLocation */
/* @var $form CActiveForm */
?>
<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm', array(
	'id'=>'customer-location-form',
	'enableAjaxValidation'=>false,
)); ?>

    <div class="row">
		<div class="col-md-4">
            <?php echo $form->dropDownListControlGroup($model, 'location_id', Location::model()->getDeliveryList(), array('prompt'=>' - Select - ')); ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->textFieldControlGroup($model, 'phone'); ?>
        </div>
    </div>
    
    <?php echo $form->textFieldControlGroup($model, 'address'); ?>
	
	<div class="row">
		<div class="col-md-4">
			<?php echo $form->textFieldControlGroup($model, 'suburb'); ?>
		</div>
		<div class="col-md-4">
            <?php echo $form->dropDownListControlGroup($model,'state',SnapUtil::config('boxomatic/states')); ?>
		</div>
		<div class="col-md-4">
			<?php echo $form->textFieldControlGroup($model, 'postcode'); ?>
		</div>
	</div>

    <div class="form-group">
        <?php echo BsHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
    </div>

<?php $this->endWidget(); ?>
