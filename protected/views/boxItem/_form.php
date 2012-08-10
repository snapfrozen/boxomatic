<?php
	Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/boxitem/_form.js',CClientScript::POS_END);
?>
<div class="form">
	<div class="section half">
		<div class="row">
			<h2>1. Select week</h2>
			<?php $this->widget('zii.widgets.grid.CGridView', array(
				'id'=>'week-grid',
				'dataProvider'=>$Weeks->search(),
	//			'filter'=>$Weeks,
				'summaryText'=>'',
				'enablePagination'=>false,
				'enableSorting'=>false,
				'selectionChanged'=>'selectBox',
				'columns'=>array(
					'week_delivery_date',
					'week_notes',
				),
			)); ?>

		</div>
	</div>
	
	<div class="section half">
		<div class="row">
			<h2>2. Select box size</h2>
			<?php $this->widget('zii.widgets.grid.CGridView', array(
				'id'=>'box-sizes-grid',
				'dataProvider'=>$BoxSizes->search(),
	//			'filter'=>$BoxSizes,
				'summaryText'=>'',
				'selectionChanged'=>'selectBox',
				'enablePagination'=>false,
				'enableSorting'=>false,
				'columns'=>array(
					'box_size_name',
					'box_size_value',
					'box_size_markup',
					'box_size_price',
				),
			)); ?>

		</div>
	</div>
	
	<div class="clear"></div>
	
	<div class="row">
		<h2>3. Select item from inventory</h2>
		<?php // echo CHtml::dropDownList('Available Items','available_growers',$growerItems); ?>
		
		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'grower-item-grid',
			'dataProvider'=>$GrowerItems->search(),
			'filter'=>$GrowerItems,
			'selectionChanged'=>'changeBoxItem',
			'columns'=>array(
				array(
					'name'=>'Grower.grower_name',
					'value'=>'$data->Grower->grower_name',
					'cssClassExpression'=>'"grower-".$data->Grower->grower_id',
				),
				'item_name',
				'item_value',
				'item_unit',
				array( 'name'=>'item_available_from', 'value'=>'Yii::app()->snapFormat->getMonthName($data->item_available_from)' ),
				array( 'name'=>'item_available_to', 'value'=>'Yii::app()->snapFormat->getMonthName($data->item_available_to)' ),
			),
		)); ?>
		
	</div>
	
	<div class="section">
		
		<h2>Items in Box</h2>
		
		<div id="current-box">
		<?php
			$this->widget('zii.widgets.grid.CGridView', array(
				'id'=>'current-items-grid',
				'dataProvider'=>$BoxItemData,
				'summaryText'=>'',
				'selectionChanged'=>'changeBoxItem',
				'enablePagination'=>false,
				'enableSorting'=>false,
				'columns'=>array(
					array(
						'name'=>'Grower.grower_name',
						'value'=>'$data->Grower->grower_name',
						'cssClassExpression'=>'"grower-".$data->Grower->grower_id',
					),
					'item_name',
					'item_value',
					'item_unit',
					'item_quantity',
					array(
						'class'=>'CButtonColumn',
						'template'=>'{myDelete}',
						'buttons'=>array(
							'myDelete' => array(
								'label'=>'Remove',
								'url'=>'array("boxItem/delete","id"=>$data->box_item_id)',
								'click'=>'deleteBoxItem'
							),
						)
					)
				),
			));
		?>
		</div>
		
	</div>
	
	
	<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'box-item-form',
	'enableAjaxValidation'=>false,
	)); ?>	
	
	<div class="section half">
		
		<h2>Box stats</h2>
		<div id="totals">
			<?php if($CurrentBox): ?>
			
			<div class="row">
				<?php echo $form->labelEx($CurrentBox,'totalValue'); ?>
				<?php echo CHtml::textField('total_value', $CurrentBox->totalValue,array('size'=>10,'maxlength'=>10,'disabled'=>'disabled')); ?>
			</div>
			
			<div class="row">
				<?php echo $form->labelEx($CurrentBox,'withMarkup'); ?>
				<?php echo CHtml::textField('markup_value', $CurrentBox->retailPrice, array('size'=>10,'maxlength'=>10,'disabled'=>'disabled')); ?>
			</div>
		
			<?php endif; ?>
		</div>
		
	</div>
	
	<div class="section half padLeft">

		<?php echo $form->errorSummary($model); ?>
		
		<h2>Add/Update item</h2>

		<?php echo $form->hiddenField($model,'box_item_id'); ?>
		<?php echo $form->hiddenField($model,'box_id',array('value'=>isset($CurrentBox) ? $CurrentBox->box_id : '')); ?>
		<?php echo $form->hiddenField($model,'grower_id'); ?>

		<div class="row">
			<?php echo $form->labelEx(Grower::model(),'grower_name'); ?>
			<?php echo CHtml::textField('grower_name',$model->Grower ? $model->Grower->grower_name : '',array('size'=>45,'maxlength'=>45,'disabled'=>'disabled')); ?>
		</div>
		
		<div class="row">
			<?php echo $form->labelEx($model,'item_name'); ?>
			<?php echo $form->textField($model,'item_name',array('size'=>45,'maxlength'=>45)); ?>
			<?php echo $form->error($model,'item_name'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'item_value'); ?>
			<?php echo $form->textField($model,'item_value',array('size'=>7,'maxlength'=>7)); ?>
			<?php echo $form->error($model,'item_value'); ?>
		</div>
		
		<div class="row">
			<?php echo $form->labelEx($model,'item_unit'); ?>
			<?php echo $form->dropDownList($model,'item_unit', Yii::app()->params['itemUnits']); ?>
			<?php echo $form->error($model,'item_unit'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'item_quantity'); ?>
			<?php echo $form->textField($model,'item_quantity',array('value'=>1)); ?>
			<?php echo $form->error($model,'item_quantity'); ?>
		</div>

		<div class="row buttons">
			<?php 
				$attribs = !$BoxItemData ? array('disabled'=>'disabled') : array(); 
				$attribs += array('id'=>'addToBox')
			?>
			<?php // echo CHtml::ajaxSubmitButton($model->isNewRecord ? 'Add to box' : 'Save', '', array('replace'=>'html'), $attribs); ?>
			<?php echo CHtml::submitButton($model->isNewRecord ? 'Add to box' : 'Save', $attribs); ?>
		</div>
		
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->