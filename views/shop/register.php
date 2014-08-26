<?php
$cs = Yii::app()->clientScript;
// $cs->registerCoreScript('jquery');
$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/pages/site/register.js', CClientScript::POS_END);
?>

<?php if (Yii::app()->user->hasFlash('register')): ?>
    <div data-alert class="alert-box">
        <?php echo Yii::app()->user->getFlash('register'); ?>
        <a href="#" class="close">&times;</a>
    </div>
<?php else: ?>
    <?php
    $form = $this->beginWidget('bootstrap.widgets.BsActiveForm', array(
        'id' => 'registration-form',
        'enableClientValidation' => false,
        // 'htmlOptions' => array('class' => 'custom'),
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));
    ?>
        <div class="alert alert-info">
            If you already have a <?php echo Yii::app()->name ?> account, please <?php echo CHtml::link('login here', array('site/login')) ?>.
        </div>

        <h1>Register</h1>
        <fieldset>
            <legend>Registration Form</legend>
            <div class="row">
                <div class="col-md-4">
                    <?php echo $form->textFieldControlGroup($model, 'first_name') ?>
                </div>
                <div class="col-md-4">
                    <?php echo $form->textFieldControlGroup($model, 'last_name') ?>
                </div>
                <div class="col-md-4">
                    <?php echo $form->textFieldControlGroup($model, 'email') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?php echo $form->textFieldControlGroup($model, 'user_phone') ?>
                </div>
                <div class="col-md-6">
                    <?php echo $form->textFieldControlGroup($model, 'user_mobile') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?php echo $form->passwordFieldControlGroup($model, 'password') ?>
                </div>
                <div class="col-md-6">
                    <?php echo $form->passwordFieldControlGroup($model, 'password_repeat') ?>
                </div>
            </div>		

            <hr />

            <div class="row">
                <div class="col-md-6">
                    <?php echo $form->dropDownListControlGroup($model, 'location_id', Location::getDeliveryAndPickupList(), array(
                        'prompt' => '- Select -'
                    )) ?>
                    <p class="hint"><?php echo Yii::app()->name ?> delivers to the above locations. We can deliver 
                        to your door if you live at one of the areas listed under <b>Delivery</b> or 
                        you can pick up your items from the locations listed under <b>Pickup</b>.</p>
                </div>
                <div class="col-md-6">

                    <div class="row">
                        <div class="col-md-12">
                            <?php echo $form->textFieldControlGroup($model, 'user_address') ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <?php echo $form->textFieldControlGroup($model, 'user_suburb') ?>
                        </div>

                        <div class="col-sm-4">
                            <?php echo $form->textFieldControlGroup($model, 'user_postcode') ?>
                        </div>

                        <div class="col-sm-4">
                            <?php echo $form->dropDownListControlGroup($model, 'user_state', SnapUtil::config('boxomatic/states')) ?>
                        </div>
                    </div>

                </div>
            </div>

            <hr />

            <?php if (CCaptcha::checkRequirements()): ?>
                <div class="row captcha">
                    
                    <!--
                    <div class="col-md-6">
                        <?php echo CHtml::label('Subscribe to our mailing list', 'register'); ?>
                        <?php echo CHtml::checkbox('register', CHtml::value($_POST, 'register', true)); ?>
                    </div>
                    -->
                    <div class="col-md-3">
                        <?php echo $form->textFieldControlGroup($model, 'verify_code') ?>
                        <p class="hint">Letters are not case-sensitive.</p>
                    </div>
                    <div class="col-md-3">
                        <?php $this->widget('CCaptcha'); ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-12">
                    <input type="submit" value="Submit" class="btn btn-primary">
                </div>
            </div>
        </fieldset>
    <?php $this->endWidget(); ?>

<?php endif; ?>