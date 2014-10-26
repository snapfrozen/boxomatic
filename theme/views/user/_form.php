<?php
$form = $this->beginWidget('bootstrap.widgets.BsActiveForm', array(
    'id' => 'user-form',
    'enableAjaxValidation' => false,
        ));
?>
<fieldset>
    <legend>Update Profile</legend>

    <?php if ($form->errorSummary($model)): ?>
        <div class="col-md-12">
            <div data-alert class="alert-box alert">
                <?php echo $form->errorSummary($model); ?>
                <a href="#" class="close">&times;</a>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4">
            <?php echo $form->textFieldControlGroup($model, 'first_name'); ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->textFieldControlGroup($model, 'last_name'); ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->textFieldControlGroup($model, 'email'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?php echo $form->textFieldControlGroup($model, 'user_phone'); ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->textFieldControlGroup($model, 'user_mobile'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?php echo $form->textFieldControlGroup($model, 'user_address'); ?>
        </div>
        <div class="col-md-4">
            <?php echo $form->dropDownListControlGroup($model, 'user_state', SnapUtil::config('boxomatic/states')); ?>
        </div>
        <div class="col-md-4 end">
            <?php echo $form->textFieldControlGroup($model, 'user_postcode'); ?>
        </div>
    </div>
</fieldset>

<fieldset>
    <legend>Delivery Addresses</legend>
    <p class="text-danger"><strong>Warning!</strong> Changing your location or delivery day will delete all your future orders.</p>
    <div class="row">
        <div class="col-md-4">
            <?php echo $form->dropDownListControlGroup($model, 'delivery_location_key', $model->getDeliveryLocations()); ?>
        </div>
        <div class="col-md-4">
            <?php if($model->Location): ?>
                <?php echo $form->dropDownListControlGroup($model, 'delivery_day', $model->Location->getDeliveryDays(), array(
                    'help' => 'Select the day of week you would like delivery on.<br />',
                )); ?>
            <?php endif; ?>
        </div>
    </div>
    <?php echo CHtml::link('Add a location', array('/userLocation/create', 'id' => $model->id), array('class' => 'btn btn-sm btn-default')); ?>
    <?php
    $this->widget('zii.widgets.CListView', array(
        'dataProvider' => $custLocDataProvider,
        'summaryText' => '',
        'itemsCssClass' => 'items row location-list',
        'itemView' => '../userLocation/_view',
    ));
    ?>        
</fieldset>

<?php /*
  if ($model->Supplier): $Supplier = $model->Supplier; ?>

  <fieldset>
  <legend>Supplier Details</legend>
  <div class="col-md-12">
  <?php echo $form->labelEx($Supplier, 'website'); ?>
  <?php echo $form->textField($Supplier, 'website', array('size' => 60, 'maxlength' => 100)); ?>
  <?php echo $form->error($Supplier, 'website'); ?>
  </div>

  <div class="col-md-12">
  <?php echo $form->labelEx($Supplier, 'distance_kms'); ?>
  <?php echo $form->textField($Supplier, 'distance_kms', array('size' => 45, 'maxlength' => 45)); ?>
  <?php echo $form->error($Supplier, 'distance_kms'); ?>
  </div>

  <div class="col-md-4">
  <?php echo $form->labelEx($Supplier, 'bank_account_name'); ?>
  <?php echo $form->textField($Supplier, 'bank_account_name', array('size' => 45, 'maxlength' => 45)); ?>
  <?php echo $form->error($Supplier, 'bank_account_name'); ?>
  </div>
  <div class="col-md-4">
  <?php echo $form->labelEx($Supplier, 'bank_bsb'); ?>
  <?php echo $form->textField($Supplier, 'bank_bsb', array('size' => 45, 'maxlength' => 45)); ?>
  <?php echo $form->error($Supplier, 'bank_bsb'); ?>
  </div>
  <div class="col-md-4">
  <?php echo $form->labelEx($Supplier, 'bank_acc'); ?>
  <?php echo $form->textField($Supplier, 'bank_acc', array('size' => 45, 'maxlength' => 45)); ?>
  <?php echo $form->error($Supplier, 'bank_acc'); ?>
  </div>
  <div class="col-md-6">
  <?php echo $form->labelEx($Supplier, 'certification_status'); ?>
  <?php echo $form->textField($Supplier, 'certification_status', array('size' => 60, 'maxlength' => 150)); ?>
  <?php echo $form->error($Supplier, 'certification_status'); ?>
  </div>
  <div class="col-md-6">
  <?php echo $form->labelEx($Supplier, 'order_days'); ?>
  <?php echo $form->textField($Supplier, 'order_days', array('size' => 60, 'maxlength' => 255)); ?>
  <?php echo $form->error($Supplier, 'order_days'); ?>
  </div>
  <div class="col-md-12 column">
  <?php echo $form->labelEx($Supplier, 'produce'); ?>
  <?php echo $form->textArea($Supplier, 'produce', array('rows' => 6, 'cols' => 50)); ?>
  <?php echo $form->error($Supplier, 'produce'); ?>
  </div>
  <div class="col-md-12">
  <?php echo $form->labelEx($Supplier, 'notes'); ?>
  <?php echo $form->textArea($Supplier, 'notes', array('rows' => 6, 'cols' => 50)); ?>
  <?php echo $form->error($Supplier, 'notes'); ?>
  </div>
  <div class="col-md-12">
  <?php echo $form->labelEx($Supplier, 'payment_details'); ?>
  <?php echo $form->textArea($Supplier, 'payment_details', array('rows' => 6, 'cols' => 50)); ?>
  <?php echo $form->error($Supplier, 'payment_details'); ?>
  </div>
  </fieldset>

  <?php endif; */ ?>

<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'btn btn-primary')); ?>

<?php $this->endWidget(); ?>