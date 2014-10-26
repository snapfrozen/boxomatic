<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle = Yii::app()->name . ' - Login';
$this->breadcrumbs = array(
    'Login',
);
?>

<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <h1>Login</h1>
        <?php
        $form = $this->beginWidget('bootstrap.widgets.BsActiveForm', array(
            'id' => 'login-form',
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
        ));
        ?>
        <?php echo $form->textFieldControlGroup($model, 'username'); ?>
        <?php echo $form->passwordFieldControlGroup($model, 'password'); ?>
        <?php echo $form->checkBoxControlGroup($model, 'rememberMe'); ?>
        <div class="form-group">
            <?php echo BsHtml::submitButton('Login'); ?>
        </div>
        <?php $this->endWidget(); ?>
        <p><?php echo CHtml::link('Forgotten your password?', array('user/forgottenPassword')) ?></p>
    </div>
</div>