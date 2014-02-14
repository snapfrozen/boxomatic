<?php
	$baseUrl = Yii::app()->request->baseUrl;
	$cs = Yii::app()->clientScript;
	$cs->registerCoreScript('jquery.ui');
	$cs->registerScriptFile($baseUrl . '/js/vendor/tag-it.min.js', CClientScript::POS_END);
	$cs->registerCssFile($baseUrl . '/css/jquery.tagit.css');
	$cs->registerCssFile($baseUrl . '/css/tagit.ui-zendesk.css');
?>
<script type="text/javascript">
	var availableTags = <?php echo json_encode(array_values(Tag::getList())) ?>;
</script>
<div class="row">
	<div class="large-12 columns">
		<!-- <h1><?php echo $model->full_name; ?></h1> -->
		<h1>Manage Profile</h1>
	</div>

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'user-form',
		'enableAjaxValidation'=>false,
		// 'htmlOptions' => array('class' => 'custom'), 
	)); ?>

	<div class="large-8 columns">
		<fieldset>
			<legend>Personal Information</legend>
			<div class="row">
				<div class="large-12 columns">
					<?php echo CHtml::link('Change password', array('changePassword','id'=>$model->id), array('class' => 'button small')); ?>
				</div>
				<div class="large-12 columns">
					<?php echo $form->labelEx($model,'user_email'); ?>
					<?php echo $form->textField($model,'user_email',array('size'=>60,'maxlength'=>255)); ?>
					<?php echo $form->error($model,'user_email'); ?>
				</div>
				<div class="large-6 columns">
					<?php echo $form->labelEx($model,'first_name'); ?>
					<?php echo $form->textField($model,'first_name',array('size'=>45,'maxlength'=>45)); ?>
					<?php echo $form->error($model,'first_name'); ?>
				</div>
				<div class="large-6 columns">
					<?php echo $form->labelEx($model,'last_name'); ?>
					<?php echo $form->textField($model,'last_name',array('size'=>45,'maxlength'=>45)); ?>
					<?php echo $form->error($model,'last_name'); ?>
				</div>

				<?php if($model->isNewRecord): ?>
				<div class="large-6 columns">
					<?php echo $form->labelEx($model,'password'); ?>
					<?php echo $form->passwordField($model,'password',array('size'=>60,'maxlength'=>255)); ?>
					<?php echo $form->error($model,'password'); ?>
				</div>
				<div class="large-6 columns">
					<?php echo $form->labelEx($model,'password_repeat'); ?>
					<?php echo $form->passwordField($model,'password_repeat',array('size'=>60,'maxlength'=>255)); ?>
					<?php echo $form->error($model,'password_repeat'); ?>
				</div>
				<?php endif; ?>

				<div class="large-6 columns">
					<?php echo $form->labelEx($model,'user_phone'); ?>
					<?php echo $form->textField($model,'user_phone',array('size'=>45,'maxlength'=>45)); ?>
					<?php echo $form->error($model,'user_phone'); ?>
				</div>
				<div class="large-6 columns">
					<?php echo $form->labelEx($model,'user_mobile'); ?>
					<?php echo $form->textField($model,'user_mobile',array('size'=>45,'maxlength'=>45)); ?>
					<?php echo $form->error($model,'user_mobile'); ?>
				</div>

				<div class="large-12 columns">
					<?php echo $form->labelEx($model,'user_address'); ?>
					<?php echo $form->textField($model,'user_address',array('size'=>60,'maxlength'=>150)); ?>
					<?php echo $form->error($model,'user_address'); ?>
				</div>
				
				<div class="large-4 columns">
					<?php echo $form->labelEx($model,'user_suburb'); ?>
					<?php echo $form->textField($model,'user_suburb',array('size'=>45,'maxlength'=>45)); ?>
					<?php echo $form->error($model,'user_suburb'); ?>
				</div>
				<div class="large-4 columns">
					<?php echo $form->labelEx($model,'user_state'); ?>
					<?php echo $form->dropDownList($model,'user_state',Yii::app()->params['states']); ?>
					<?php echo $form->error($model,'user_state'); ?>
				</div>
				<div class="large-4 columns">
					<?php echo $form->labelEx($model,'user_postcode'); ?>
					<?php echo $form->textField($model,'user_postcode',array('size'=>45,'maxlength'=>45)); ?>
					<?php echo $form->error($model,'user_postcode'); ?>
				</div>
			</div>
		</fieldset>

		<?php if($model->Customer && Yii::app()->user->checkAccess('Admin')): $Customer=$model->Customer; ?>

		<fieldset>
			<legend>Customer Details</legend>
			<?php echo $form->labelEx($Customer,'customer_notes'); ?>
			<?php echo $form->textArea($Customer,'customer_notes',array('rows'=>6, 'cols'=>50)); ?>
			<?php echo $form->error($Customer,'customer_notes'); ?>
			
			<?php echo $form->labelEx($Customer,'tags'); ?>
			<?php echo $form->hiddenField($Customer, 'tag_names'); ?>
		</fieldset>

		<?php endif; ?>

		<?php if($model->Supplier): $Supplier=$model->Supplier; ?>
		
		<fieldset>
			<legend>Supplier Details</legend>
			<div class="large-12 columns">
				<?php echo $form->labelEx($Supplier,'website'); ?>
				<?php echo $form->textField($Supplier,'website',array('size'=>60,'maxlength'=>100)); ?>
				<?php echo $form->error($Supplier,'website'); ?>
			</div>
			<div class="large-12 columns">
				<?php echo $form->labelEx($Supplier,'distance_kms'); ?>
				<?php echo $form->textField($Supplier,'distance_kms',array('size'=>45,'maxlength'=>45)); ?>
				<?php echo $form->error($Supplier,'distance_kms'); ?>
			</div>
			<div class="large-12 columns">
				<?php echo $form->labelEx($Supplier,'bank_account_name'); ?>
				<?php echo $form->textField($Supplier,'bank_account_name',array('size'=>45,'maxlength'=>45)); ?>
				<?php echo $form->error($Supplier,'bank_account_name'); ?>
			</div>
			<div class="large-6 columns">
				<?php echo $form->labelEx($Supplier,'bank_bsb'); ?>
			<?php echo $form->textField($Supplier,'bank_bsb',array('size'=>45,'maxlength'=>45)); ?>
			<?php echo $form->error($Supplier,'bank_bsb'); ?>
			</div>
			<div class="large-6 columns">
				<?php echo $form->labelEx($Supplier,'bank_acc'); ?>
				<?php echo $form->textField($Supplier,'bank_acc',array('size'=>45,'maxlength'=>45)); ?>
				<?php echo $form->error($Supplier,'bank_acc'); ?>
			</div>
			<div class="large-6 columns">
				<?php echo $form->labelEx($Supplier,'certification_status'); ?>
				<?php echo $form->textField($Supplier,'certification_status',array('size'=>60,'maxlength'=>150)); ?>
				<?php echo $form->error($Supplier,'certification_status'); ?>
			</div>
			<div class="large-6 columns">
				<?php echo $form->labelEx($Supplier,'order_days'); ?>
				<?php echo $form->textField($Supplier,'order_days',array('size'=>60,'maxlength'=>255)); ?>
				<?php echo $form->error($Supplier,'order_days'); ?>
			</div>
			<div class="large-12 columns">
				<?php echo $form->labelEx($Supplier,'produce'); ?>
				<?php echo $form->textArea($Supplier,'produce',array('rows'=>6, 'cols'=>50)); ?>
				<?php echo $form->error($Supplier,'produce'); ?>
			</div>
			<div class="large-12 columns">
				<?php echo $form->labelEx($Supplier,'notes'); ?>
				<?php echo $form->textArea($Supplier,'notes',array('rows'=>6, 'cols'=>50)); ?>
				<?php echo $form->error($Supplier,'notes'); ?>
			</div>
			<div class="large-12 columns">
				<?php echo $form->labelEx($Supplier,'payment_details'); ?>
				<?php echo $form->textArea($Supplier,'payment_details',array('rows'=>6, 'cols'=>50)); ?>
				<?php echo $form->error($Supplier,'payment_details'); ?>
			</div>
		</fieldset>
		<?php endif; ?>		

		<div class="row">
			<div class="large-12 columns">
				<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'button')); ?>	
			</div>
		</div>
	</div>
	<div class="large-4 columns">
		<?php if($model->Customer): $Customer=$model->Customer; ?>
		<fieldset>
			<legend>Delivery Addresses</legend>
			<div class="row">
				<div class="large-12 columns">
					<?php echo $form->label($Customer, 'delivery_location_key');  ?>
					<?php echo $form->dropDownList($Customer, 'delivery_location_key', $Customer->getDeliveryLocations());  ?>
					<p>Changing this will update all your order locations</p>
					<?php echo CHtml::link('Add a location', array('customerLocation/create','custId'=>$model->customer_id), array('class' => 'button small')); ?>
					<?php $this->widget('zii.widgets.CListView', array(
						'dataProvider'=>$custLocDataProvider,
						'summaryText' => '',
						'itemView'=>'../customerLocation/_view',
					)); ?>
				</div>
			</div>
		</fieldset>
		<?php endif; ?>

		<?php if(Yii::app()->user->checkAccess('Admin')): ?>
		<div class="panel callout">
			<h4>Administration Panel</h4>
			<?php echo CHtml::label('Role','role') ?>
			<?php echo CHtml::dropDownList('role',$model->getRole(),CHtml::listData(Yii::app()->authManager->getRoles(),'name','name')); ?>
		</div>
		<?php endif; ?>
	</div>
	
	<?php $this->endWidget(); ?>
</div>