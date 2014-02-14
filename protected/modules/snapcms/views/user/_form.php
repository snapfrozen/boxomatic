<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form row">
	
<?php $form=$this->beginWidget('SnapActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('class'=>'col-md-12'),
)); ?>

	<?php //echo $form->errorSummary($model); ?>
	
	<ul class="nav nav-tabs">
		<li class="active"><a href="#details" data-toggle="tab">Details</a></li>
		<li><a href="#groups" data-toggle="tab">Groups</a></li>
	</ul>

	<div class="tab-content">
	
		<div id="details" class="tab-pane active">
			
			<div class="form-group <?php echo $model->hasErrors('first_name') ? 'has-error' : ''; ?>">
				<?php echo $form->labelEx($model,'first_name'); ?>
				<?php echo $form->textField($model,'first_name',array('size'=>60,'maxlength'=>255,'placeholder'=>'User\'s first name')); ?>
				<?php echo $form->error($model,'first_name',array('class'=>'help-block')); ?>
			</div>

			<div class="form-group <?php echo $model->hasErrors('last_name') ? 'has-error' : ''; ?>">
				<?php echo $form->labelEx($model,'last_name'); ?>
				<?php echo $form->textField($model,'last_name',array('size'=>60,'maxlength'=>255,'placeholder'=>'User\'s last name')); ?>
				<?php echo $form->error($model,'last_name',array('class'=>'help-block')); ?>
			</div>

			<div class="form-group <?php echo $model->hasErrors('email') ? 'has-error' : ''; ?>">
				<?php echo $form->labelEx($model,'email'); ?>
				<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>255,'placeholder'=>'User\'s email address')); ?>
				<?php echo $form->error($model,'email',array('class'=>'help-block')); ?>
			</div>
			
			<?php if($model->isNewRecord): ?>
			<div class="form-group <?php echo $model->hasErrors('password') ? 'has-error' : ''; ?>">
				<?php echo $form->labelEx($model,'password',array('class'=>'control-label')); ?>
				<?php echo $form->textField($model,'password',array('size'=>60,'maxlength'=>255,'placeholder'=>'Enter password')); ?>
				<?php echo $form->error($model,'password',array('class'=>'help-block')); ?>
			</div>
			<?php endif; ?>

		</div>

		<div id="groups" class="tab-pane">
			<div class="form-group">
				<?php echo CHtml::checkBoxList(
					'UserGroups', 
					CHtml::listData($userGroups,'name','name'), 
					CHtml::listData($groups,'name','name')); ?>
			</div>
		</div>
		
	</div>

	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class'=>'btn btn-primary btn-lg')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->