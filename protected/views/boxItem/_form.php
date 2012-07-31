<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'box-item-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
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
					'week_starting',
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
						'template'=>'{delete}'
					)
				),
			));
		?>
		</div>
		
	</div>
	
	<div class="section half">
		
		<h2>Box totals</h2>
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

		<h2>Add item</h2>

		<?php echo $form->hiddenField($model,'box_id',array('value'=>isset($CurrentBox) ? $CurrentBox->box_id : '')); ?>
		<?php echo $form->error($model,'box_id'); ?>
		<?php echo $form->hiddenField($model,'grower_id'); ?>
		<?php echo $form->error($model,'grower_id'); ?>

		<div class="row">
			<?php echo $form->labelEx(Grower::model(),'grower_name'); ?>
			<?php echo CHtml::textField('grower_name','',array('size'=>45,'maxlength'=>45,'disabled'=>'disabled')); ?>
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
			<?php echo $form->dropDownList($model,'item_unit',  Yii::app()->params['itemUnits']); ?>
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

<script type="text/javascript">

	var $ItemName = $('#BoxItem_item_name');
	var $ItemUnit = $('#BoxItem_item_unit');
	var $ItemGrower = $('#BoxItem_grower_id');
	var $ItemGrowerName = $('#grower_name');
	var $ItemValue = $('#BoxItem_item_value');
	var $ItemBox = $('#BoxItem_box_id');
	var $submitButton = $('#addToBox');
	
	var boxSizesGridId = 'box-sizes-grid';
	var weekGridId = 'week-grid';
	var growerItemGridId = 'grower-item-grid';
	var currentItemsGridId = 'current-items-grid';

	$submitButton.click(function(){
		var weekId = $.fn.yiiGridView.getSelection(weekGridId);
		var sizeId = $.fn.yiiGridView.getSelection(boxSizesGridId);
		
		var url = $('#box-item-form').attr('action') + '&weekId=' + weekId + '&sizeId=' + sizeId;;
		var data = $('#box-item-form').serialize();
		
		//Only do the ajax query if we have a week and size
		if(!weekId.length || !sizeId.length) return false;
	
		var ajaxUpdate = ['current-box','BoxItem_box_id','totals'];
		$.ajax({
			type: 'POST',
			url: url,
			data: data,
			success: function(data,status) {
				$.each(ajaxUpdate, function(i,v) {
					var id='#'+v;
					$(id).replaceWith($(id,'<div>'+data+'</div>'));
				});
				updateSubmitButton();
			}
		});
		
		return false;
	});

	function changeBoxItem(id)
	{
		if(id == currentItemsGridId)
			$('#' + growerItemGridId).find('tr.selected').removeClass('selected');
		else if(id == growerItemGridId)
			$('#' + currentItemsGridId).find('tr.selected').removeClass('selected');
		
		var $gridTable = $('#'+id+' tbody');
		var $selectedRow = $gridTable.find('> tr.selected');
		var selectedIndex = $gridTable.find('> tr').index($selectedRow);
		var cells = $.fn.yiiGridView.getRow(id,selectedIndex);

		var growerItemId = $.fn.yiiGridView.getSelection(id);
		var growerId = cells[0].className.replace('grower-','');
		var growerName = cells[0].innerHTML;
		var itemName = cells[1].innerHTML;
		var itemValue = cells[2].innerHTML;
		var itemUnit = cells[3].innerHTML;

		$ItemName.val(itemName);
		$ItemUnit.val(itemUnit);
		$ItemValue.val(itemValue);
		$ItemGrower.val(growerId);
		$ItemGrowerName.val(growerName);
		
		updateSubmitButton();
	}
	
	function selectBox(id)
	{
		var settings = $.fn.yiiGridView.settings[id];
		var weekId = $.fn.yiiGridView.getSelection(weekGridId);
		var sizeId = $.fn.yiiGridView.getSelection(boxSizesGridId);
		
		var url = $.fn.yiiGridView.getUrl(id) + '&weekId=' + weekId + '&sizeId=' + sizeId;
		
		//Only do the ajax query if we have a week and size
		if(!weekId.length || !sizeId.length) return false;
		
		$('#'+id).addClass(settings.loadingClass);
		var ajaxUpdate = ['current-box','BoxItem_box_id','totals'];
		$.ajax({
			type: 'GET',
			url: url,
			success: function(data,status) {
				$.each(ajaxUpdate, function(i,v) {
					var id='#'+v;
					$(id).replaceWith($(id,'<div>'+data+'</div>'));
				});
				$('#'+id).removeClass(settings.loadingClass);
				$.fn.yiiGridView.selectCheckedRows(id);
				
				updateSubmitButton();
			}
		});
	}
	
	function updateSubmitButton()
	{
		var weekId = $.fn.yiiGridView.getSelection(weekGridId);
		var sizeId = $.fn.yiiGridView.getSelection(boxSizesGridId);
		var growerId = $ItemGrower.val();
		
		if(weekId.length && sizeId.length && growerId)
			$submitButton.removeAttr('disabled');
	}

</script>