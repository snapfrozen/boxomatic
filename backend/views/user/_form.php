<?php
	$baseUrl = $this->createFrontendUrl('/').'/themes/boxomatic/admin';
	$cs = Yii::app()->clientScript;
	$cs->registerScriptFile($baseUrl . '/js/vendor/tag-it.min.js', CClientScript::POS_END);
	$cs->registerCssFile($baseUrl . '/css/jquery.tagit.css');
	$cs->registerCssFile($baseUrl . '/css/tagit.ui-zendesk.css');
?>
<script type="text/javascript">
	var availableTags = <?php echo json_encode(array_values(Tag::getList())) ?>;
</script>
<div class="form">
<?php $form=$this->beginWidget('application.widgets.SnapActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
	'layout' => BsHtml::FORM_LAYOUT_HORIZONTAL,
	'htmlOptions' => array('class'=>'row'),
)); ?>
	<div class="col-lg-9 clearfix">
		<?php echo $form->textFieldControlGroup($model,'first_name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->textFieldControlGroup($model,'last_name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->textFieldControlGroup($model,'email',array('size'=>255,'maxlength'=>255)); ?>
		
		<?php if($model->isNewRecord): ?>
		<?php echo $form->textFieldControlGroup($model,'password',array('size'=>255,'maxlength'=>255)); ?>
		<?php echo $form->textFieldControlGroup($model,'password_repeat',array('size'=>255,'maxlength'=>255)); ?>
		<?php endif; ?>
		
		<?php echo $form->textFieldControlGroup($model,'user_phone',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->textFieldControlGroup($model,'user_mobile',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->textAreaControlGroup($model,'user_address'); ?>
		<?php echo $form->dropDownListControlGroup($model,'user_state',SnapUtil::config('boxomatic/states')); ?>
		<?php echo $form->textFieldControlGroup($model,'user_postcode',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->textAreaControlGroup($model,'notes'); ?>
		
		<div class="form-group">
			<?php echo $form->label($model,'tags',array('class'=>'control-label col-lg-2')); ?>
			<div class="col-lg-10">
				<?php echo $form->hiddenField($model, 'tag_names'); ?>
			</div>
		</div>
		
		<?php if(Yii::app()->user->checkAccess('Admin')): ?>
		<?php echo BsHtml::dropDownListControlGroup('role',$model->getRole(),CHtml::listData(Yii::app()->authManager->getRoles(),'name','name'),array(
			'formLayout'=>$form->layout,
			'label'=>'Role',
		)) ?>
		
		<fieldset>
			<legend>Delivery Addresses</legend>
			
			<?php echo $form->dropDownListControlGroup($model,'delivery_location_key', $model->getDeliveryLocations(),array(
				'help'=>'Changing this will update all your order locations',
			)); ?>
			<div class="row">
				<div class="col-md-offset-2 col-md-10">
					<p><?php echo CHtml::link('Add a location', array('userLocation/create','custId'=>$model->id), array('class' => 'btn btn-sm btn-primary')); ?></p>
					<div class="row">
					<?php $this->widget('bootstrap.widgets.BsListView', array(
						'dataProvider'=>$custLocDataProvider,
						'summaryText' => '',
						'itemView'=>'../userLocation/_view',
						'id'=>'delivery-locations'
					)); ?>
					</div>
				</div>
			</div>
		</fieldset>

		
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

			<div class="large-4 columns">
				<?php echo $form->labelEx($Supplier,'bank_account_name'); ?>
				<?php echo $form->textField($Supplier,'bank_account_name',array('size'=>45,'maxlength'=>45)); ?>
				<?php echo $form->error($Supplier,'bank_account_name'); ?>
			</div>
			<div class="large-4 columns">
				<?php echo $form->labelEx($Supplier,'bank_bsb'); ?>
				<?php echo $form->textField($Supplier,'bank_bsb',array('size'=>45,'maxlength'=>45)); ?>
				<?php echo $form->error($Supplier,'bank_bsb'); ?>
			</div>
			<div class="large-4 columns">
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
			<div class="large-12 column">
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

		<?php endif;  ?>
		
		<?php endif; ?>
	</div>
	<div id="sidebar" class="col-lg-3">
		<?php $this->beginWidget('bootstrap.widgets.BsPanel', array(
			'title'=>'Menu',
			'contentCssClass'=>'',
			'htmlOptions'=>array(
				'class'=>'panel sticky',
			),
			'type'=>BsHtml::PANEL_TYPE_PRIMARY,
		)); ?>		
		<div class="btn-group btn-group-vertical">
			<?php echo BsHtml::submitButton(BsHtml::icon(BsHtml::GLYPHICON_THUMBS_UP).' Save'); ?>
			
			<?php $this->widget('application.widgets.SnapMenu', array(
				'items'=>$this->menu,
				'htmlOptions'=>array('class'=>'nav nav-stacked'),
			)); ?>			
		</div>
		<?php $this->endWidget(); ?>	
	</div>
	
</div>

<?php $this->endWidget(); ?>