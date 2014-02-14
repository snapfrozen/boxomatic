<?php
	Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/customerbox/_form.js',CClientScript::POS_END);
?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'customer-box-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<?php $this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'box-sizes-grid',
		'dataProvider'=>$Boxes->search(),
		'filter'=>$Boxes,
		'summaryText'=>'',
		'selectionChanged'=>'selectBox',
		'enablePagination'=>false,
		'enableSorting'=>false,
		'columns'=>array(
			array(
				'name'=>'size_id',
				'value'=>'$data->BoxSize->box_size_name',
				'filter'=>CHtml::listData(BoxSize::model()->findAll(), 'id', 'box_size_name'),
			),
			array(
				'name'=>'box_price',
				'filter'=>CHtml::listData(Boxes::model()->findAll(array('order'=>'box_price')), 'box_price', 'box_price'),
			),
			array(
				'name'=>'delivery_date_id',
				'value'=>'Yii::app()->dateFormatter->format("EEE, MMM d",$data->DeliveryDate->date)',
				'filter'=>CHtml::listData(DeliveryDate::model()->findAll(array('order'=>'date')), 'id', 'date'),
			)
		),
	)); ?>
	
	<div class="row quantity">
		<?php echo $form->labelEx($model,'quantity'); ?>
		<?php echo $form->textField($model,'quantity',array('class'=>'number')); ?>
		<?php echo $form->error($model,'quantity'); ?>
	</div>
	
	<div id="selected-box">
		<?php if($SelectedBox): ?>
		<div class="half">
			<h2>Items in box</h2>
			<?php if($items): ?>
				<?php 
				$this->widget('zii.widgets.CListView', array(
					'summaryText'=>'',
					'enablePagination'=>false,
					'enableSorting'=>false,
					'dataProvider'=>$items,
					'itemView'=>'../boxItem/_view',
				));
				?>
			<?php endif; ?>
		</div>

		<div class="half padLeft">
			<h2>Order Information</h2>
			<?php echo $form->hiddenField($model,'box_id'); ?>

			<?php $Location=$model->Customer->Location; ?>

			<div class="info">
				<div class="row">
					<span class="label"><?php echo $Location->getAttributeLabel('location_name') ?></span>
					<span class="value"><?php echo $Location->location_name; ?></span>
				</div>
				<div class="row">
					<span class="label"><?php echo $model->quantity ?> x <?php echo $SelectedBox->BoxSize->box_size_name; ?> Box</span>
					<span class="value number"><?php echo $model->total_box_price; ?></span>
				</div>
				
				<?php if((int)$model->total_delivery_price): //only show delivery cost if its greater than 0 ?>
				<div class="row">
					<span class="label"><?php echo $model->quantity ?> x <?php echo $Location->getAttributeLabel('location_delivery_value') ?></span>
					<span class="value number"><?php echo $model->total_delivery_price; ?></span>
				</div>
				<?php endif; ?>
				
				<div class="row total">
					<span class="label"><?php echo $model->getAttributeLabel('total_price') ?></span>
					<span class="value number"><?php echo $model->total_price; ?></span>
				</div>
			</div>
			<div class="row buttons">
				<?php echo CHtml::submitButton($model->isNewRecord ? 'Place order' : 'Update order'); ?>
			</div>

		</div>
	<?php endif; ?>
	</div>
	

<?php $this->endWidget(); ?>

</div><!-- form -->
