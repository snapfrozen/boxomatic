<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - Contact Us';
$this->breadcrumbs=array(
	'Contact',
);
?>

<h1>Contact Us</h1>

<?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php else: ?>

<p>
If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.
</p>

<div class="row">
<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm', array(
	'id'=>'contact-form',
	//'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	'htmlOptions'=>array(
		'class'=>'col-md-5'
	),
)); ?>
<fieldset>
	
	<?php echo $form->errorSummary($model); ?>

	<?php
		echo $form->textFieldControlGroup($model, 'name');
		echo $form->textFieldControlGroup($model, 'email');
		echo $form->textFieldControlGroup($model, 'subject');
		echo $form->textAreaControlGroup($model, 'email');
	?>
	
	<?php if(CCaptcha::checkRequirements()): ?>
	
	<div class="form-group">
		<?php echo $form->labelEx($model,'verifyCode'); ?>
		<div>
			<?php $this->widget('CCaptcha'); ?>
			<?php echo $form->textField($model,'verifyCode'); ?>
		</div>
		<p class="help-block">Please enter the letters as they are shown in the image above.
		<br/>Letters are not case-sensitive.</p>
		<?php echo $form->error($model,'verifyCode'); ?>
	</div>
	
	<?php endif; ?>

	<?php echo BsHtml::submitButton('Submit', array(
		'color' => BsHtml::BUTTON_COLOR_PRIMARY
	)); ?>
	
</fieldset>
<?php $this->endWidget(); ?>

<?php endif; ?>

</div>