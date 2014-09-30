<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <h1>Forgotten Password</h1>
        <?php if($mailError): ?>
		<div class="alert alert-danger alert-dismissable">
			Problem sending mail.
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		</div>
		<?php elseif($User): ?>
		<div class="alert alert-success alert-dismissable">
			We have sent you a password renewal email to your email address.
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		</div>
		<?php endif; ?>
        
        <?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm', array(
			'id'=>'forgotten-password-form',
			'enableClientValidation'=>true,
			'clientOptions'=>array(
				'validateOnSubmit'=>true,
			),
		)); ?>

		<?php echo $form->textFieldControlGroup($model, 'username', array(
            'help' => 'Enter your email address to start the password renewal process'
        )); ?>
		<div class="form-group">
            <?php echo BsHtml::submitButton('Next &gt;'); ?>
        </div>
		<?php $this->endWidget(); ?>
    </div>
</div>