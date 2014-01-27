<?php
	$cs=Yii::app()->clientScript;
	$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/ui-lightness/jquery-ui.css');
	// $cs->registerCssFile(Yii::app()->request->baseUrl . '/css/ui.spinner.css');
	$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/chosen.css');
	$cs->registerCoreScript('jquery.ui');
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/ui.touch-punch.min.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/ui.datepicker.min.js', CClientScript::POS_END);
	// $cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/ui.spinner.min.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/chosen.jquery.min.js', CClientScript::POS_END);
	// $cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/jquery.stickyscroll.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/boxitem/_form.js',CClientScript::POS_END);
	
Yii::app()->clientScript->registerScript('initPageSize',<<<EOD
	$('.change-pageSize').on('change', function() {
		$.fn.yiiGridView.update('supplier-item-grid',{ data:{ pageSize: $(this).val() }})
	});

EOD
,CClientScript::POS_READY);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'box-item-form',
	'enableAjaxValidation'=>false,
	'action'=>$this->createUrl('boxItem/create',array('date'=>Yii::app()->request->getQuery('date'))),
)); ?>

<div class="secondaryNav">
	
	<div class="large-3 columns">
		<div class="calendar">
			<script type="text/javascript">
				var curUrl="<?php echo $this->createUrl('boxItem/create'); ?>";
				var selectedDate=<?php echo $SelectedDeliveryDate ? "'$SelectedDeliveryDate->date'" : 'null' ?>;
				var availableDates=<?php echo json_encode(SnapUtil::makeArray($DeliveryDates)) ?>;
			</script>
			<div class="dropDown">
				<h4 data-dropdown="calendarDropdown">Date: <strong><?php echo $SelectedDeliveryDate ? Yii::app()->snapFormat->date($SelectedDeliveryDate->date) : 'None Selected'; ?></strong></h4>
				<div class="dropDownPanel" id="calendarDropdown">
					<div class="delivery-date-picker"></div>
					<noscript>
					<?php foreach($DeliveryDates as $DeliveryDate): ?>
						<?php echo CHtml::link($DeliveryDate->date, array('boxItem/create','date'=>$DeliveryDate->id)) ?>, 
					<?php endforeach; ?>
					</noscript>
				</div>
			</div>
		</div>
	</div>
	<?php if($SelectedDeliveryDate): ?>
	<div class="large-3 columns">
		<div id="inventory">
			<div class="dropDown">
				<h4 data-dropdown="inventoryDropdown">Inventory</h4>
				<div id="inventoryDropdown" class="dropDownPanel">
				<?php $dataProvider=$SupplierProducts->search(); ?>
				<?php $pageSize=Yii::app()->user->getState('pageSize',10); ?>
				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'id'=>'supplier-item-grid',
					'cssFile' => '', 
					'dataProvider'=>$dataProvider,
					'filter'=>$SupplierProducts,
					'summaryText'=>'' .
					CHtml::dropDownList(
						'pageSize',
						$pageSize,
						array(5=>5,10=>10,20=>20,50=>50,100=>100),
						array('class'=>'change-pageSize')) .
					' rows per page',
					'rowCssClassExpression'=>'$data->id==Yii::app()->request->getQuery("item") ? "active" : null',
					'selectableRows'=>0,
					//'selectionChanged'=>'changeBoxItem',
					'columns'=>array(
						array(
							'name'=>'supplier_search',
							'type'=>'raw',
							'value'=>'
								Yii::app()->request->getQuery("date") ?
									CHtml::link($data->Supplier->name,array_merge(array("boxItem/create","item"=>$data->id,"date"=>Yii::app()->request->getQuery("date"))))
								:
									$data->Supplier->name',
							'cssClassExpression'=>'"supplier-".$data->Supplier->id',
							//'filter'=>$SupplierProducts
						),
						'name',
						array(
							'name'=>'value',
							'value'=>'Yii::app()->snapFormat->currency($data->value)',
						),
						array( 
							'name'=>'month_available', 
							'value'=>'Yii::app()->snapFormat->getMonthName($data->available_from) . " to " . Yii::app()->snapFormat->getMonthName($data->available_to)',
							'filter'=>Yii::app()->params["months"],
						),
						array(
							'class'=>'SnapButtonColumn',
							'template'=>'{update}{delete}',
							'buttons'=>array(
								'update'=>array(
									'url'=>'array("supplierProduct/update","id"=>$data->id)',
								),
								'delete'=>array(
									'url'=>'array("supplierProduct/delete","id"=>$data->id)',
								)
							)
						)
					),
				)); ?>
				</div>
			</div>
		</div>
	</div>
	
	<div class="large-3 columns end">
		<div class="addSupplier">
			<div class="dropDown">
				<h4 data-dropdown="supplierDropdown">Suppliers</h4>
				<div class="dropDownPanel" id="supplierDropdown">
					<div class="large-12 columns">
						<?php echo CHtml::dropDownList('new_supplier',null,CHtml::listData(Supplier::model()->findAll(array('order'=>'name ASC')),'id','name'),array('class'=>'chosen')); ?>
						<?php echo CHtml::hiddenField('selected_delivery_date_id',$SelectedDeliveryDate->id); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>

</div>

<div class="large-12 columns">
	<div class='right'>
		<?php echo CHtml::submitButton('Update Boxes', array('class' => 'button small')); ?>
	</div>
	<h4><span>Boxes</span> <span class="loading"></span></h4>
	<div class="clear"></div>

	<div id="current-boxes">

		<?php echo CHtml::hiddenField('curUrl', $this->createUrl('boxItem/create',array('date'=>Yii::app()->request->getQuery('date')))); ?>
		<?php if($SelectedDeliveryDate): ?>

		<table>
			<thead>
				<tr>
					<th class="supplierName">Items</th>
					<th class="valueCol">Value</th>
					<?php
					$dateBoxCount=0;
					foreach($DeliveryDateBoxes as $DeliveryDateBoxMerged): 
						$dateBoxIds=explode(',',$DeliveryDateBoxMerged->box_ids);

						foreach($dateBoxIds as $pos=>$dateBoxId): 
						$DateBox=Box::model()->findByPk($dateBoxId);
						?>
						<th class="boxCol">
							<?php echo $DateBox->customerCount ?><br />
							<?php echo $DateBox->BoxSize->box_size_name ?>
							<div><div>
								<?php if($DateBox->customerCount && isset($dateBoxIds[$pos-1])): ?>
									<?php echo CHtml::link('<', array('box/moveBox','from'=>$dateBoxId,'to'=>$dateBoxIds[$pos-1]), array('title'=>'Move a box from this variation')); ?>
								<?php endif; ?>

								<?php echo CHtml::link('duplicate', array('box/duplicate','id'=>$dateBoxId)); ?>

								<?php if($DateBox->customerCount && isset($dateBoxIds[$pos+1])): ?>
									<?php echo CHtml::link('>', array('box/moveBox','from'=>$dateBoxId,'to'=>$dateBoxIds[$pos+1]), array('title'=>'Move a box from this variation')); ?>
								<?php endif; ?>

								<?php if(!$DateBox->customerCount && count($dateBoxIds) > 1): ?>
									<br />
									<?php echo CHtml::link('delete', array('box/delete','id'=>$dateBoxId)); ?>
								<?php endif; ?>
							</div></div>
						</th>
						<?php $dateBoxCount++ ?>
						<?php endforeach; ?>

					<?php endforeach; ?>
					<th class="totalCol">Box Total</th>
					<th class="quantityCol">Quantity</th>
				</tr>
			</thead>
			<tbody>					
			<?php 
			$lastSupplierId=null;
			foreach($SelectedDeliveryDate->BoxItemsContent as $key=>$BoxItemsContent): 

				if($lastSupplierId != $BoxItemsContent->supplier_id): 
				$lastSupplierId=$BoxItemsContent->supplier_id;
				?>
				<tr class="group">
					<td colspan="<?php echo $dateBoxCount+4 ?>">
						<?php echo CHtml::link($BoxItemsContent->Supplier->name, array('boxItem/create','supplier'=>$BoxItemsContent->supplier_id,'date'=>Yii::app()->request->getQuery('date'))); ?>
						(<strong><?php echo Yii::app()->snapFormat->currency(BoxItem::supplierTotalByDeliveryDate($BoxItemsContent->supplier_id, $SelectedDeliveryDate->id)) ?></strong>)
					</td>
				</tr>
				<?php endif; ?>

				<?php $selectedClass=in_array($selectedItemId,explode(',',$BoxItemsContent->box_item_ids)) ? 'class="selected"' : ''; ?>

				<tr <?php echo $selectedClass ?>>
					<td>
						<?php echo CHtml::checkbox('bc['.$key.'][add_to_inventory]',false,array('title'=>'Add this item to the inventory', 'class' => 'inline')); ?>
						<?php
							echo CHtml::hiddenField('bc['.$key.'][supplier_id]',$BoxItemsContent->supplier_id);
							echo CHtml::hiddenField('bc['.$key.'][date_id]',$SelectedDeliveryDate->id);
							echo CHtml::textField('bc['.$key.'][item_name]',$BoxItemsContent->item_name, array('class' => 'inline-85'));

							$totalQuantity=BoxItem::totalQuantity($BoxItemsContent->box_item_ids);
							$totalValue=$BoxItemsContent->item_value*$totalQuantity;
						?>
					</td>
					<td class="itemValue">
						<?php echo CHtml::textField('bc['.$key.'][item_value]',$BoxItemsContent->item_value,array('class'=>'currency')); ?> 
						<?php echo CHtml::dropDownList('bc['.$key.'][item_unit]',$BoxItemsContent->item_unit,Yii::app()->params['itemUnits']); ?>
					</td>
					<?php
					$key2=0;
					foreach($DeliveryDateBoxes as $DeliveryDateBoxMerged): 
						$dateBoxIds=explode(',',$DeliveryDateBoxMerged->box_ids);

						foreach($dateBoxIds as $dateBoxId): 
							$Box=Box::model()->findByPk($dateBoxId);

							$BoxItem=BoxItem::model()->with('Box')->find(
								'item_name=:itemName AND 
								supplier_id=:supplierId AND 
								item_unit=:itemUnit AND 
								item_value=:itemValue AND 
								Box.delivery_date_id=:dateId AND 
								Box.size_id=:sizeId AND
								t.box_id = ' . $Box->box_id, 
								array (
									':itemName'=>$BoxItemsContent->item_name,
									':supplierId'=>$BoxItemsContent->supplier_id,
									':itemUnit'=>$BoxItemsContent->item_unit,
									':itemValue'=>$BoxItemsContent->item_value,
									':dateId'=>$Box->delivery_date_id,
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
					foreach($DeliveryDateBoxes as $DeliveryDateBoxMerged): 
						$dateBoxIds=explode(',',$DeliveryDateBoxMerged->box_ids);

						foreach($dateBoxIds as $dateBoxId): 
							$DateBox=Box::model()->findByPk($dateBoxId);
							//$value=$SelectedDeliveryDate->with(array('totalBoxValue'=>array('params'=>array(':sizeId'=>$DateBox->size_id))))->findByPk($SelectedDeliveryDate->id)->totalBoxValue;
							$value=$DateBox->totalValue;
							$totalValue+=$value;
						?>
						<td class="value"><?php echo Yii::app()->snapFormat->currency($value) ?></td>
						<?php endforeach; ?>
					<?php endforeach; ?>
					<td class="value"><strong><?php echo Yii::app()->snapFormat->currency(BoxItem::dateWholesale($SelectedDeliveryDate->id)) ?></strong></td>
					<td></td>
				</tr>
				<tr>
					<td class="total" colspan="2">
						Target Retail:
					</td>
					<?php 
					$totalRetal=0;
					foreach($DeliveryDateBoxes as $DeliveryDateBoxMerged): 
						$dateBoxIds=explode(',',$DeliveryDateBoxMerged->box_ids);

						foreach($dateBoxIds as $dateBoxId): 
							$DateBox=Box::model()->findByPk($dateBoxId);
							$totalRetal+=$DateBox->box_price;
						?>
						<td class="value"><?php echo Yii::app()->snapFormat->currency($DateBox->box_price) ?></td>
						<?php endforeach; ?>
					<?php endforeach; ?>
					<td class="value"><strong><?php echo Yii::app()->snapFormat->currency(BoxItem::dateTarget($SelectedDeliveryDate->id)) ?></strong></td>
					<td></td>
				</tr>
				<tr>
					<td class="total" colspan="2">
						Box Markup:
					</td>
					<?php 
					$totalRetal=0;
					foreach($DeliveryDateBoxes as $DeliveryDateBoxMerged): 
						$dateBoxIds=explode(',',$DeliveryDateBoxMerged->box_ids);

						foreach($dateBoxIds as $dateBoxId): 
							$DateBox=Box::model()->findByPk($dateBoxId);
						?>
						<td class="value">%<?php echo $DateBox->BoxSize->box_size_markup ?></td>
						<?php endforeach; ?>
					<?php endforeach; ?>
					<td class="value"><strong><?php echo Yii::app()->snapFormat->currency(BoxItem::dateTarget($SelectedDeliveryDate->id)) ?></strong></td>
					<td></td>
				</tr>
				<tr>
					<td class="total" colspan="2">
						Box Retail:
					</td>
					<?php 
					$totalRetal=0;
					foreach($DeliveryDateBoxes as $DeliveryDateBoxMerged): 
						$dateBoxIds=explode(',',$DeliveryDateBoxMerged->box_ids);

						foreach($dateBoxIds as $dateBoxId): 
							$DateBox=Box::model()->findByPk($dateBoxId);
							$retail=$value=$DateBox->retailPrice;
							$totalRetal+=$retail;
						?>
						<td class="value <?php echo $retail > $DateBox->box_price ? 'red' : '' ?>"><?php echo Yii::app()->snapFormat->currency($retail) ?></td>
						<?php endforeach; ?>
					<?php endforeach; ?>
					<td class="value"><strong><?php echo Yii::app()->snapFormat->currency(BoxItem::dateRetail($SelectedDeliveryDate->id)) ?></strong></td>
					<td></td>
				</tr>

			</tfoot>
		</table>
	</div>

	<div class="panel">
		<?php echo CHtml::link('Generate packing list Spreadsheet',array('deliveryDate/generatePackingList','date'=>$SelectedDeliveryDate->id), array('class' => 'button small')) ?>
		<?php echo CHtml::link('Generate customer list',array('deliveryDate/generateCustomerList','date'=>$SelectedDeliveryDate->id), array('class' => 'button small')) ?>
		<?php echo CHtml::link('Generate customer list PDF',array('deliveryDate/generateCustomerListPdf','date'=>$SelectedDeliveryDate->id), array('class' => 'button small')) ?>
		<?php echo CHtml::link('Generate order list',array('deliveryDate/generateOrderList','date'=>$SelectedDeliveryDate->id), array('class' => 'button small')) ?>
	</div>
	<?php endif; ?>
	
</div>

<?php $this->endWidget(); ?>