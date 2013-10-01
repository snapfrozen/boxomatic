<?php
/* @var $this GrowerPurchaseController */
/* @var $model GrowerPurchase */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'grower_purchases_id'); ?>
		<?php echo $form->textField($model,'grower_purchases_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'grower_item_id'); ?>
		<?php echo $form->textField($model,'grower_item_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'propsed_quantity'); ?>
		<?php echo $form->textField($model,'propsed_quantity',array('size'=>7,'maxlength'=>7)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'propsed_price'); ?>
		<?php echo $form->textField($model,'propsed_price',array('size'=>7,'maxlength'=>7)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'proposed_delivery_date'); ?>
		<?php echo $form->textField($model,'proposed_delivery_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'order_notes'); ?>
		<?php echo $form->textArea($model,'order_notes',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'delivered_quantity'); ?>
		<?php echo $form->textField($model,'delivered_quantity',array('size'=>7,'maxlength'=>7)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'final_price'); ?>
		<?php echo $form->textField($model,'final_price',array('size'=>7,'maxlength'=>7)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'delivery_nots'); ?>
		<?php echo $form->textArea($model,'delivery_nots',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->