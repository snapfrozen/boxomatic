<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

	
	<div class="half">

		<?php echo $form->errorSummary($model); ?>

		<div class="row">
			<?php echo $form->labelEx($model,'user_email'); ?>
			<?php echo $form->textField($model,'user_email',array('size'=>60,'maxlength'=>255)); ?>
			<?php echo $form->error($model,'user_email'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'first_name'); ?>
			<?php echo $form->textField($model,'first_name',array('size'=>45,'maxlength'=>45)); ?>
			<?php echo $form->error($model,'first_name'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'last_name'); ?>
			<?php echo $form->textField($model,'last_name',array('size'=>45,'maxlength'=>45)); ?>
			<?php echo $form->error($model,'last_name'); ?>
		</div>

		<?php if($model->isNewRecord): ?>
		<div class="row">
			<?php echo $form->labelEx($model,'password'); ?>
			<?php echo $form->passwordField($model,'password',array('size'=>60,'maxlength'=>255)); ?>
			<?php echo $form->error($model,'password'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'password_repeat'); ?>
			<?php echo $form->passwordField($model,'password_repeat',array('size'=>60,'maxlength'=>255)); ?>
			<?php echo $form->error($model,'password_repeat'); ?>
		</div>
		<?php endif ?>

		<div class="row">
			<?php echo $form->labelEx($model,'user_phone'); ?>
			<?php echo $form->textField($model,'user_phone',array('size'=>45,'maxlength'=>45)); ?>
			<?php echo $form->error($model,'user_phone'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'user_mobile'); ?>
			<?php echo $form->textField($model,'user_mobile',array('size'=>45,'maxlength'=>45)); ?>
			<?php echo $form->error($model,'user_mobile'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'user_address'); ?>
			<?php echo $form->textField($model,'user_address',array('size'=>60,'maxlength'=>150)); ?>
			<?php echo $form->error($model,'user_address'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'user_address2'); ?>
			<?php echo $form->textField($model,'user_address2',array('size'=>60,'maxlength'=>150)); ?>
			<?php echo $form->error($model,'user_address2'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'user_suburb'); ?>
			<?php echo $form->textField($model,'user_suburb',array('size'=>45,'maxlength'=>45)); ?>
			<?php echo $form->error($model,'user_suburb'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'user_state'); ?>
			<?php echo $form->textField($model,'user_state',array('size'=>45,'maxlength'=>45)); ?>
			<?php echo $form->error($model,'user_state'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'user_postcode'); ?>
			<?php echo $form->textField($model,'user_postcode',array('size'=>45,'maxlength'=>45)); ?>
			<?php echo $form->error($model,'user_postcode'); ?>
		</div>

		<?php if($model->Customer): $Customer=$model->Customer; ?>

		<h2>Customer Details</h2>

		<div class="row">
			<?php echo $form->labelEx($Customer,'customer_notes'); ?>
			<?php echo $form->textArea($Customer,'customer_notes',array('rows'=>6, 'cols'=>50)); ?>
			<?php echo $form->error($Customer,'customer_notes'); ?>
		</div>

		<?php endif; ?>

		<?php if($model->Grower): $Grower=$model->Grower; ?>

		<h2>Grower Details</h2>

		<div class="row">
			<?php echo $form->labelEx($Grower,'grower_website'); ?>
			<?php echo $form->textField($Grower,'grower_website',array('size'=>60,'maxlength'=>100)); ?>
			<?php echo $form->error($Grower,'grower_website'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($Grower,'grower_distance_kms'); ?>
			<?php echo $form->textField($Grower,'grower_distance_kms',array('size'=>45,'maxlength'=>45)); ?>
			<?php echo $form->error($Grower,'grower_distance_kms'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($Grower,'grower_bank_account_name'); ?>
			<?php echo $form->textField($Grower,'grower_bank_account_name',array('size'=>45,'maxlength'=>45)); ?>
			<?php echo $form->error($Grower,'grower_bank_account_name'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($Grower,'grower_bank_bsb'); ?>
			<?php echo $form->textField($Grower,'grower_bank_bsb',array('size'=>45,'maxlength'=>45)); ?>
			<?php echo $form->error($Grower,'grower_bank_bsb'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($Grower,'grower_bank_acc'); ?>
			<?php echo $form->textField($Grower,'grower_bank_acc',array('size'=>45,'maxlength'=>45)); ?>
			<?php echo $form->error($Grower,'grower_bank_acc'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($Grower,'grower_certification_status'); ?>
			<?php echo $form->textField($Grower,'grower_certification_status',array('size'=>60,'maxlength'=>150)); ?>
			<?php echo $form->error($Grower,'grower_certification_status'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($Grower,'grower_order_days'); ?>
			<?php echo $form->textField($Grower,'grower_order_days',array('size'=>60,'maxlength'=>255)); ?>
			<?php echo $form->error($Grower,'grower_order_days'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($Grower,'grower_produce'); ?>
			<?php echo $form->textArea($Grower,'grower_produce',array('rows'=>6, 'cols'=>50)); ?>
			<?php echo $form->error($Grower,'grower_produce'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($Grower,'grower_notes'); ?>
			<?php echo $form->textArea($Grower,'grower_notes',array('rows'=>6, 'cols'=>50)); ?>
			<?php echo $form->error($Grower,'grower_notes'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($Grower,'grower_payment_details'); ?>
			<?php echo $form->textArea($Grower,'grower_payment_details',array('rows'=>6, 'cols'=>50)); ?>
			<?php echo $form->error($Grower,'grower_payment_details'); ?>
		</div>

		<?php endif; ?>

		<?php if(Yii::app()->user->checkAccess('admin')): ?>
		<div class="row">
			<?php echo CHtml::label('Role','role') ?>
			<?php echo CHtml::dropDownList('role',$model->getRole(),CHtml::listData(Yii::app()->authManager->getRoles(),'name','name')) ?>
		</div>
		<?php endif; ?>

		<?php if(CCaptcha::checkRequirements() && $model->isNewRecord): ?>
		<div class="row">
			<?php echo $form->labelEx($model,'verifyCode'); ?>
			<div>
			<?php $this->widget('CCaptcha'); ?>
			<?php echo $form->textField($model,'verifyCode'); ?>
			</div>
			<div class="hint">Please enter the letters as they are shown in the image above.
			<br/>Letters are not case-sensitive.</div>
			<?php echo $form->error($model,'verifyCode'); ?>
		</div>
		<?php endif; ?>

		<div class="row buttons">
			<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
		</div>
	
	</div>
	
	<?php if($model->Customer): $Customer=$model->Customer; ?>
	<div class="half padLeft">
		
		<h2>Delivery addresses</h2>

		<div class="row">
			<?php echo $form->label($Customer, 'delivery_location_key');  ?>
			<?php echo $form->dropDownList($Customer, 'delivery_location_key', $Customer->getDeliveryLocations());  ?>
			<p class="hint">Changing this will update all your order locations</p>
		</div>
			
		<p><?php echo CHtml::link('Add a location', array('customerLocation/create','custId'=>$model->customer_id)); ?></p>
		<?php $this->widget('zii.widgets.CListView', array(
			'dataProvider'=>$custLocDataProvider,
			'itemView'=>'../customerLocation/_view',
		)); ?>
		
	</div>
	<?php endif; ?>
	
	
<?php $this->endWidget(); ?>

</div><!-- form -->