<?php
	$cs=Yii::app()->clientScript;
	$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/ui-lightness/jquery-ui.css');
	$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/ui.spinner.css');
	$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/chosen.css');
	
	$cs->registerCoreScript('jquery.ui');
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/ui.touch-punch.min.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/ui.datepicker.min.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/ui.spinner.min.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/chosen.jquery.min.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/jquery.stickyscroll.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/boxitem/_form.js',CClientScript::POS_END);
	
Yii::app()->clientScript->registerScript('initPageSize',<<<EOD
	$('.change-pageSize').live('change', function() {
		$.fn.yiiGridView.update('grower-item-grid',{ data:{ pageSize: $(this).val() }})
	});
EOD
,CClientScript::POS_READY);
?>
<div id="fillBoxForm" class="form">
	
	<div class="calendar">
		<h2>Delivery date</h2>
		<div class="row">
			<script type="text/javascript">
				var curUrl="<?php echo $this->createUrl('boxItem/create'); ?>";
				var selectedDate=<?php echo $SelectedWeek ? "'$SelectedWeek->week_delivery_date'" : 'null' ?>;
				var availableWeeks=<?php echo json_encode(SnapUtil::makeArray($Weeks)) ?>;
			</script>
			<div class="week-picker"></div>
			<noscript>
			<?php foreach($Weeks as $Week): ?>
				<?php echo CHtml::link($Week->week_delivery_date, array('boxItem/create','week'=>$Week->week_id)) ?>, 
			<?php endforeach; ?>
			</noscript>
		</div>
	</div>

	<div id="inventory" class="row">
		<h2>Inventory</h2>
		<?php $dataProvider=$GrowerItems->search(); ?>
		<?php $pageSize=Yii::app()->user->getState('pageSize',10); ?>
		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'grower-item-grid',
			'dataProvider'=>$dataProvider,
			'filter'=>$GrowerItems,
			'summaryText'=>'Displaying {start}-{end} of {count} result(s). ' .
			CHtml::dropDownList(
				'pageSize',
				$pageSize,
				array(5=>5,10=>10,20=>20,50=>50,100=>100),
				array('class'=>'change-pageSize')) .
			' rows per page',
			'rowCssClassExpression'=>'$data->item_id==Yii::app()->request->getQuery("item") ? "active" : null',
			'selectableRows'=>0,
			//'selectionChanged'=>'changeBoxItem',
			'columns'=>array(
				array(
					'name'=>'grower_search',
					'type'=>'raw',
					'value'=>'
						Yii::app()->request->getQuery("week") ?
							CHtml::link($data->Grower->grower_name,array_merge(array("boxItem/create","item"=>$data->item_id,"week"=>Yii::app()->request->getQuery("week"))))
						:
							$data->Grower->grower_name',
					'cssClassExpression'=>'"grower-".$data->Grower->grower_id',
					//'filter'=>$GrowerItems
				),
				'item_name',
				array(
					'name'=>'item_value',
					'value'=>'Yii::app()->snapFormat->currency($data->item_value)',
				),
				array( 
					'name'=>'month_available', 
					'value'=>'Yii::app()->snapFormat->getMonthName($data->item_available_from) . " to " . Yii::app()->snapFormat->getMonthName($data->item_available_to)',
					'filter'=>Yii::app()->params["months"],
				),
				array(
					'class'=>'CButtonColumn',
					'template'=>'{update}{delete}',
					'buttons'=>array(
						'update'=>array(
							'url'=>'array("growerItem/update","id"=>$data->item_id)',
						),
						'delete'=>array(
							'url'=>'array("growerItem/delete","id"=>$data->item_id)',
						)
					)
				)
			),
		)); ?>
		
	</div>
	
	<div class="clear"></div>
	
	<div class="section">
		<h2><span>Boxes</span> <span class="loading"></span></h2>
		<div class="clear"></div>
		<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'box-item-form',
		'enableAjaxValidation'=>false,
		'action'=>$this->createUrl('boxItem/create',array('week'=>Yii::app()->request->getQuery('week'))),
		)); ?>
		
		<div id="current-boxes">
			
			<?php echo CHtml::hiddenField('curUrl', $this->createUrl('boxItem/create',array('week'=>Yii::app()->request->getQuery('week')))); ?>
			<?php if($SelectedWeek): ?>
			<div class="row">
				<?php echo CHtml::dropDownList('new_grower',null,CHtml::listData(Grower::model()->findAll(array('order'=>'grower_name ASC')),'grower_id','grower_name'),array('class'=>'chosen')); ?>
				<?php echo CHtml::hiddenField('selected_week_id',$SelectedWeek->week_id); ?>
				<div class="sticky" style="float:right">
					<?php echo CHtml::submitButton('Update Boxes'); ?>
				</div>
			</div>
			<table>
				<thead>
					<tr>
						<th class="growerName">Items</th>
						<th>Value</th>
						<?php
						$weekBoxCount=0;
						foreach($WeekBoxes as $WeekBoxMerged): 
							$weekBoxIds=explode(',',$WeekBoxMerged->box_ids);
						
							foreach($weekBoxIds as $pos=>$weekBoxId): 
							$WeekBox=Box::model()->findByPk($weekBoxId);
							?>
							<th>
								<?php echo $WeekBox->customerCount ?><br />
								<?php echo $WeekBox->BoxSize->box_size_name ?>
								<div><div>
									<?php if($WeekBox->customerCount && isset($weekBoxIds[$pos-1])): ?>
										<?php echo CHtml::link('<', array('box/moveBox','from'=>$weekBoxId,'to'=>$weekBoxIds[$pos-1]), array('title'=>'Move a box from this variation')); ?>
									<?php endif; ?>
										
									<?php echo CHtml::link('duplicate', array('box/duplicate','id'=>$weekBoxId)); ?>

									<?php if($WeekBox->customerCount && isset($weekBoxIds[$pos+1])): ?>
										<?php echo CHtml::link('>', array('box/moveBox','from'=>$weekBoxId,'to'=>$weekBoxIds[$pos+1]), array('title'=>'Move a box from this variation')); ?>
									<?php endif; ?>
										
									<?php if(!$WeekBox->customerCount && count($weekBoxIds) > 1): ?>
										<br />
										<?php echo CHtml::link('delete', array('box/delete','id'=>$weekBoxId)); ?>
									<?php endif; ?>
								</div></div>
							</th>
							<?php $weekBoxCount++ ?>
							<?php endforeach; ?>
							
						<?php endforeach; ?>
						<th>Box Total</th>
						<th>Quantity</th>
					</tr>
				</thead>
				<tbody>					
				<?php 
				$lastGrowerId=null;
				foreach($SelectedWeek->BoxItemsContent as $key=>$WeekItemContent): 

					if($lastGrowerId != $WeekItemContent->grower_id): 
					$lastGrowerId=$WeekItemContent->grower_id;
					?>
					<tr class="group">
						<td colspan="<?php echo $weekBoxCount+4 ?>">
							<?php echo CHtml::link($WeekItemContent->Grower->grower_name, array('boxItem/create','grower'=>$WeekItemContent->grower_id,'week'=>Yii::app()->request->getQuery('week'))); ?>
							(<strong><?php echo Yii::app()->snapFormat->currency(BoxItem::growerTotalByWeek($WeekItemContent->grower_id, $SelectedWeek->week_id)) ?></strong>)
						</td>
					</tr>
					<?php endif; ?>

					<?php $selectedClass=in_array($selectedItemId,explode(',',$WeekItemContent->box_item_ids)) ? 'class="selected"' : ''; ?>
						
					<tr <?php echo $selectedClass ?>>
						<td>
							<?php echo CHtml::checkbox('bc['.$key.'][add_to_inventory]',false,array('title'=>'Add this item to the inventory')); ?>
							<?php
								echo CHtml::hiddenField('bc['.$key.'][grower_id]',$WeekItemContent->grower_id);
								echo CHtml::hiddenField('bc['.$key.'][week_id]',$SelectedWeek->week_id);
								echo CHtml::textField('bc['.$key.'][item_name]',$WeekItemContent->item_name);
								
								$totalQuantity=BoxItem::totalQuantity($WeekItemContent->box_item_ids);
								$totalValue=$WeekItemContent->item_value*$totalQuantity;
							?>
						</td>
						<td class="itemValue">
							<?php echo CHtml::textField('bc['.$key.'][item_value]',$WeekItemContent->item_value,array('class'=>'currency')); ?> 
							<?php echo CHtml::dropDownList('bc['.$key.'][item_unit]',$WeekItemContent->item_unit,Yii::app()->params['itemUnits']); ?>
						</td>
						<?php
						$key2=0;
						foreach($WeekBoxes as $WeekBoxMerged): 
							$weekBoxIds=explode(',',$WeekBoxMerged->box_ids);
						
							foreach($weekBoxIds as $weekBoxId): 
								$Box=Box::model()->findByPk($weekBoxId);

								$BoxItem=BoxItem::model()->with('Box')->find(
									'item_name=:itemName AND 
									grower_id=:growerId AND 
									item_unit=:itemUnit AND 
									item_value=:itemValue AND 
									Box.week_id=:weekId AND 
									Box.size_id=:sizeId AND
									t.box_id = ' . $Box->box_id, 
									array (
										':itemName'=>$WeekItemContent->item_name,
										':growerId'=>$WeekItemContent->grower_id,
										':itemUnit'=>$WeekItemContent->item_unit,
										':itemValue'=>$WeekItemContent->item_value,
										':weekId'=>$Box->week_id,
										':sizeId'=>$Box->size_id
									)
								); 

								?>
								<td><?php 
									if($BoxItem): 
										echo CHtml::textField('bc['.$key.'][BoxItem]['.$key2.'][item_quantity]', $BoxItem->item_quantity, array(
											'class'=>'decimal','min'=>0,'title'=>'Retail: '. Yii::app()->snapFormat->currency($BoxItem->retail_price) .'  Wholesale: '. Yii::app()->snapFormat->currency($BoxItem->wholesale_price) ));
										echo CHtml::hiddenField('bc['.$key.'][BoxItem]['.$key2.'][box_item_id]', $BoxItem->box_item_id);
										echo CHtml::hiddenField('bc['.$key.'][BoxItem]['.$key2.'][box_id]', $Box->box_id);
									else:
										echo CHtml::textField('bc['.$key.'][BoxItem]['.$key2.'][item_quantity]', 0, array('class'=>'decimal','min'=>0));
										echo CHtml::hiddenField('bc['.$key.'][BoxItem]['.$key2.'][box_id]', $Box->box_id);
									endif;
									$key2++;
								?></td>
							<?php endforeach; ?>
						<?php endforeach; ?>
						<td class="value">
							<?php echo Yii::app()->snapFormat->currency($totalValue) ?>
						</td>
						<td class="value">
							<?php echo (float)$totalQuantity ?>
						</td>	
					</tr>
				<?php endforeach;?>
				</tbody>
				<tfoot>
					<tr>
						<td class="total" colspan="2">
							Box Wholesale:
						</td>
						<?php 
						$totalValue=0;
						foreach($WeekBoxes as $WeekBoxMerged): 
							$weekBoxIds=explode(',',$WeekBoxMerged->box_ids);
						
							foreach($weekBoxIds as $weekBoxId): 
								$WeekBox=Box::model()->findByPk($weekBoxId);
								//$value=$SelectedWeek->with(array('totalBoxValue'=>array('params'=>array(':sizeId'=>$WeekBox->size_id))))->findByPk($SelectedWeek->week_id)->totalBoxValue;
								$value=$WeekBox->totalValue;
								$totalValue+=$value;
							?>
							<td class="value"><?php echo Yii::app()->snapFormat->currency($value) ?></td>
							<?php endforeach; ?>
						<?php endforeach; ?>
						<td class="value"><strong><?php echo Yii::app()->snapFormat->currency(BoxItem::weekWholesale($SelectedWeek->week_id)) ?></strong></td>
						<td></td>
					</tr>
					<tr>
						<td class="total" colspan="2">
							Target Retail:
						</td>
						<?php 
						$totalRetal=0;
						foreach($WeekBoxes as $WeekBoxMerged): 
							$weekBoxIds=explode(',',$WeekBoxMerged->box_ids);
						
							foreach($weekBoxIds as $weekBoxId): 
								$WeekBox=Box::model()->findByPk($weekBoxId);
								$totalRetal+=$WeekBox->box_price;
							?>
							<td class="value"><?php echo Yii::app()->snapFormat->currency($WeekBox->box_price) ?></td>
							<?php endforeach; ?>
						<?php endforeach; ?>
						<td class="value"><strong><?php echo Yii::app()->snapFormat->currency(BoxItem::weekTarget($SelectedWeek->week_id)) ?></strong></td>
						<td></td>
					</tr>
					<tr>
						<td class="total" colspan="2">
							Box Markup:
						</td>
						<?php 
						$totalRetal=0;
						foreach($WeekBoxes as $WeekBoxMerged): 
							$weekBoxIds=explode(',',$WeekBoxMerged->box_ids);
						
							foreach($weekBoxIds as $weekBoxId): 
								$WeekBox=Box::model()->findByPk($weekBoxId);
							?>
							<td class="value">%<?php echo $WeekBox->BoxSize->box_size_markup ?></td>
							<?php endforeach; ?>
						<?php endforeach; ?>
						<td class="value"><strong><?php echo Yii::app()->snapFormat->currency(BoxItem::weekTarget($SelectedWeek->week_id)) ?></strong></td>
						<td></td>
					</tr>
					<tr>
						<td class="total" colspan="2">
							Box Retail:
						</td>
						<?php 
						$totalRetal=0;
						foreach($WeekBoxes as $WeekBoxMerged): 
							$weekBoxIds=explode(',',$WeekBoxMerged->box_ids);
						
							foreach($weekBoxIds as $weekBoxId): 
								$WeekBox=Box::model()->findByPk($weekBoxId);
								$retail=$value=$WeekBox->retailPrice;
								$totalRetal+=$retail;
							?>
							<td class="value <?php echo $retail > $WeekBox->box_price ? 'red' : '' ?>"><?php echo Yii::app()->snapFormat->currency($retail) ?></td>
							<?php endforeach; ?>
						<?php endforeach; ?>
						<td class="value"><strong><?php echo Yii::app()->snapFormat->currency(BoxItem::weekRetail($SelectedWeek->week_id)) ?></strong></td>
						<td></td>
					</tr>
					
				</tfoot>
			</table>
			<p><?php echo CHtml::link('Generate packing list',array('week/generatePackingList','week'=>$SelectedWeek->week_id)) ?></p>
			<p><?php echo CHtml::link('Generate customer list',array('week/generateCustomerList','week'=>$SelectedWeek->week_id)) ?></p>
			<p><?php echo CHtml::link('Generate order list',array('week/generateOrderList','week'=>$SelectedWeek->week_id)) ?></p>
		<?php endif; ?>
		</div>
		
		<?php $this->endWidget(); ?>
	</div>

	<div class="section half">

	</div>

</div><!-- form -->