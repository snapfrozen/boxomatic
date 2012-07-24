<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'grower_id'); ?>
		<?php echo $form->textField($model,'grower_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'grower_name'); ?>
		<?php echo $form->textField($model,'grower_name',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'grower_mobile'); ?>
		<?php echo $form->textField($model,'grower_mobile',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'grower_phone'); ?>
		<?php echo $form->textField($model,'grower_phone',array('size'=>60,'maxlength'=>150)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'grower_address'); ?>
		<?php echo $form->textField($model,'grower_address',array('size'=>60,'maxlength'=>150)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'grower_address2'); ?>
		<?php echo $form->textField($model,'grower_address2',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'grower_suburb'); ?>
		<?php echo $form->textField($model,'grower_suburb',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'grower_state'); ?>
		<?php echo $form->textField($model,'grower_state',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'grower_postcode'); ?>
		<?php echo $form->textField($model,'grower_postcode',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'grower_distance_kms'); ?>
		<?php echo $form->textField($model,'grower_distance_kms',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'grower_bank_account_name'); ?>
		<?php echo $form->textField($model,'grower_bank_account_name',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'grower_bank_bsb'); ?>
		<?php echo $form->textField($model,'grower_bank_bsb',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'grower_bank_acc'); ?>
		<?php echo $form->textField($model,'grower_bank_acc',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'grower_email'); ?>
		<?php echo $form->textField($model,'grower_email',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'grower_website'); ?>
		<?php echo $form->textField($model,'grower_website',array('size'=>60,'maxlength'=>150)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'grower_certification_status'); ?>
		<?php echo $form->textField($model,'grower_certification_status',array('size'=>60,'maxlength'=>150)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'grower_order_days'); ?>
		<?php echo $form->textField($model,'grower_order_days',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'grower_produce'); ?>
		<?php echo $form->textArea($model,'grower_produce',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'grower_notes'); ?>
		<?php echo $form->textArea($model,'grower_notes',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'grower_payment_details'); ?>
		<?php echo $form->textArea($model,'grower_payment_details',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->