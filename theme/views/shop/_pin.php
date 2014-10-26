<div class="form">
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id'=>'pin-payment-form',
    'enableClientValidation'=>true,
    'htmlOptions'=>array(
        'role'=>'form',
        'class'=>'form-horizontal'
    ),
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
));
?>
<fieldset>
    <legend>Payment</legend>
    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model, null, null, array('class'=>'alert alert-block alert-danger')); ?>

    <?php if(isset(Yii::app()->session['errors'])): ?>
    <div class="alert alert-block alert-danger" id="pin-errors">
        <p><?php $errors = Yii::app()->session['errors']; echo $errors['error_description']; ?></p>
        <ul>
        <?php foreach($errors['messages'] as $messages): ?>
            <li><?php echo $messages['message']; ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="form-group">
        <?php echo $form->labelEx($model,'name', array('class' => 'col-sm-3 control-label')); ?>
        <div class="col-sm-9">
            <?php echo $form->textField($model,'name', array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'name'); ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model,'number', array('class' => 'col-sm-3 control-label')); ?>
        <div class="col-sm-9">
            <?php echo $form->textField($model,'number', array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'number'); ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model,'expiry_month', array('class' => 'col-sm-3 control-label')); ?>
        <div class="col-sm-9">
            <div class="row">
                <div class="col-xs-3">
                    <?php echo $form->dropDownList($model,'expiry_month',SnapUtil::config('boxomatic/months'),array('class'=>'form-control')); ?>
                    <?php echo $form->error($model,'expiry_month'); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model,'expiry_year', array('class' => 'col-sm-3 control-label')); ?>
        <div class="col-sm-9">
            <div class="row">
                <div class="col-xs-3">
                    <?php
                    $cr = 2014;
                    $years = [];
                    for($i = $cr;$i < $cr+20; $i++) {
                        $years[$i] = $i;
                    }
                    echo $form->dropDownList($model,'expiry_year',$years,array('class'=>'form-control'));
                    ?>
                    <?php echo $form->error($model,'expiry_year'); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model,'cvc', array('class' => 'col-sm-3 control-label')); ?>
        <div class="col-sm-9">
            <?php echo $form->textField($model,'cvc', array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'cvc'); ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model,'email', array('class' => 'col-sm-3 control-label')); ?>
        <div class="col-sm-9">
            <?php echo $form->textField($model,'email', array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'email'); ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model,'description', array('class' => 'col-sm-3 control-label')); ?>
        <div class="col-sm-9">
            <?php echo $form->textArea($model,'description', array('class'=>'form-control','rows'=>'5','cols'=>'60')); ?>
            <?php echo $form->error($model,'description'); ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model,'address_line1', array('class' => 'col-sm-3 control-label')); ?>
        <div class="col-sm-9">
            <?php echo $form->textField($model,'address_line1', array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'address_line1'); ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model,'address_line2', array('class' => 'col-sm-3 control-label')); ?>
        <div class="col-sm-9">
            <?php echo $form->textField($model,'address_line2', array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'address_line2'); ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model,'address_city', array('class' => 'col-sm-3 control-label')); ?>
        <div class="col-sm-9">
            <?php echo $form->textField($model,'address_city', array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'address_city'); ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model,'address_country', array('class' => 'col-sm-3 control-label')); ?>
        <div class="col-sm-9">
            <?php echo $form->textField($model,'address_country', array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'address_country'); ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model,'address_postcode', array('class' => 'col-sm-3 control-label')); ?>
        <div class="col-sm-3">
            <?php echo $form->textField($model,'address_postcode', array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'address_postcode'); ?>
        </div>
    </div>

    <input type="hidden" name="payment-method" value="<?php echo $paymentMethod; ?>" readonly/>
    <input type="hidden" name="amount" value="<?php echo $amount; ?>"/>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <?php echo CHtml::submitButton('Pay Now',array('class' => 'btn btn-success')); ?>
        </div>
    </div>
</fieldset>
<?php $this->endWidget(); ?>
</div>