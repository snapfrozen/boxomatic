<?php
$this->breadcrumbs=array(
	'Box-O-Matic'=>array('/snapcms/boxomatic/index'),
	'Boxes'=>array('boxes/index'),
	'Box Packing',
);
$this->menu=array(
	array('icon' => 'glyphicon glyphicon-thumbs-up', 'label'=>'Update Boxes', 'url'=>'#', 'linkOptions'=>array('id'=>'update-boxes')),
	array('icon' => 'glyphicon glyphicon-export', 'label'=>'Packing list spreadsheet', 'url'=>array('deliveryDate/generatePackingList','date'=>$SelectedDeliveryDate->id)),
	array('icon' => 'glyphicon glyphicon-export', 'label'=>'Customer list', 'url'=>array('deliveryDate/generateCustomerList','date'=>$SelectedDeliveryDate->id)),
	array('icon' => 'glyphicon glyphicon-export', 'label'=>'Customer list PDF', 'url'=>array('deliveryDate/generateCustomerListPdf','date'=>$SelectedDeliveryDate->id)),
	array('icon' => 'glyphicon glyphicon-export', 'label'=>'Order list', 'url'=>array('deliveryDate/generateOrderList','date'=>$SelectedDeliveryDate->id)),
);

$dateLabel = $SelectedDeliveryDate ? 'for '.SnapFormat::date($SelectedDeliveryDate->date) : 'None Selected';

//Custom header below
//$this->page_heading = 'Box Packing';
//$this->page_heading_subtext = $dateLabel;

$baseUrl = $this->createFrontendUrl('/').'/themes/boxomatic/admin';
$cs=Yii::app()->clientScript;
$cs->registerCssFile($baseUrl.'/css/ui-lightness/jquery-ui.css');
$cs->registerCssFile($baseUrl . '/css/chosen.css');
$cs->registerCoreScript('jquery.ui');
$cs->registerScriptFile($baseUrl . '/js/ui.touch-punch.min.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/ui.datepicker.min.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/chosen.jquery.min.js', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/boxitem/_form.js',CClientScript::POS_END);
	
Yii::app()->clientScript->registerScript('initPageSize',<<<EOD
	$('.change-pageSize').on('change', function() {
		$.fn.yiiGridView.update('supplier-item-grid',{ data:{ pageSize: $(this).val() }})
	});

EOD
,CClientScript::POS_READY);
?>
<div id="calendar-dropdown" class="page-header dropdown">
	<h1>Box Packing	<small class="dropdown-toggle" data-toggle="dropdown" data-target="#calendar-dropdown"><?php echo $dateLabel ?> <b class="caret"></b></small></h1>
	<div class="dropdown-menu" aria-labelledby="dLabel" role="menu">
		<li>
			<div class="calendar">
				<script type="text/javascript">
					var curUrl="<?php echo $this->createUrl('boxItem/create'); ?>";
					var selectedDate=<?php echo $SelectedDeliveryDate ? "'$SelectedDeliveryDate->date'" : 'null' ?>;
					var availableDates=<?php echo json_encode(SnapUtil::makeArray($DeliveryDates)) ?>;
				</script>
				<div class="delivery-date-picker"></div>
			</div>
		</li>
	</div>
</div>

<?php $form=$this->beginWidget('application.widgets.SnapActiveForm', array(
	'id'=>'box-item-form',
	'enableAjaxValidation'=>false,
	'action'=>$this->createUrl('boxItem/create',array('date'=>Yii::app()->request->getQuery('date'))),
	'htmlOptions'=>array('class'=>'row'),
)); ?>
<div id="supplier-list" class="col-lg-0 has-pullout">
	<?php if($SelectedDeliveryDate): ?>
	<div class="large-3 columns end">
		<div id="inventory">
			<?php
			$this->beginWidget('bootstrap.widgets.BsPanel', array(
				'title'=>'Products',
				'titleTag'=>'h3',
			));
			?>
				<?php $dataProvider=$SupplierProducts->search($SelectedDeliveryDate); ?>
				<?php $pageSize=Yii::app()->user->getState('pageSize',10); ?>
				<?php $this->widget('bootstrap.widgets.BsGridView', array(
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
									CHtml::link($data->Supplier->name,array_merge(array("boxItem/create","date"=>Yii::app()->request->getQuery("date"),"supplier"=>$data->supplier_id)))
								:
									$data->Supplier->name',
							'cssClassExpression'=>'"supplier-".$data->Supplier->id',
							//'filter'=>$SupplierProducts
						),
						array(
							'name'=>'name',
							'type'=>'raw',
							'value'=>'
								Yii::app()->request->getQuery("date") ?
									CHtml::link($data->name,array_merge(array("boxItem/create","item"=>$data->id,"date"=>Yii::app()->request->getQuery("date"))))
								:
									$data->name',
							'cssClassExpression'=>'"item-".$data->Supplier->id',
							//'filter'=>$SupplierProducts
						),
						array(
							'name'=>'value',
							'value'=>'SnapFormat::currency($data->value)',
						),
						array( 
							'name'=>'month_available', 
							'value'=>'SnapFormat::getMonthName($data->available_from,"M") . " to " . SnapFormat::getMonthName($data->available_to,"M")',
							'filter'=>SnapUtil::config('boxomatic/months'),
						),
						/*
						array(
							'name'=>'in_inventory',
							'type'=>'raw',
							'value'=>'empty($data->Inventory) ? "No" : "Yes"',
							'filter'=>array(1=>'Yes',0=>'No'),
						),
						 */
						array(
							'class'=>'bootstrap.widgets.BsButtonColumn',
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
			<?php $this->endWidget(); ?>
		</div>
	</div>
	
	<?php endif; ?>
	<div class="pullout">
		<div id="supplier-pullout" class="arrow-right arrow-wrap">
			<span class="arrow"></span>
		</div>
	</div>
</div>

<div id="packing-list" class="col-lg-10">
	<div id="current-boxes">
		<?php echo CHtml::hiddenField('curUrl', $this->createUrl('boxItem/create',array('date'=>Yii::app()->request->getQuery('date')))); ?>
		<?php if($SelectedDeliveryDate): ?>
		<ul class="nav nav-tabs">
			<li class="active">
				<a data-toggle="tab" href="#boxes">Boxes <span class="loading"></span></a>
			</li>
			<li>
				<a data-toggle="tab" href="#customers">Box Customers</a>
			</li>
		</ul>
		<div class="tab-content">
			<div id="boxes" class="tab-pane active">
				<?php $this->beginWidget('bootstrap.widgets.BsPanel'); ?>
				<table class="table">
					<thead>
						<tr>
							<th class="supplierName">Items</th>
							<th class="packingStation">Packing Station</th>
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
									<div class="dropdown">
										<a href="#" data-toggle="dropdown" class="dropdown-toggle"><?php echo $DateBox->name ?> <b class="caret"></b></a>
										<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
											<?php if($DateBox->customerCount && isset($dateBoxIds[$pos-1])): ?>
											<li><?php echo CHtml::link('<span class="glyphicon glyphicon-arrow-left"></span> Move 1 Box', array('box/moveBox','from'=>$dateBoxId,'to'=>$dateBoxIds[$pos-1]), array('title'=>'Move a box from this variation')); ?></li>
											<?php endif; ?>

											<li><?php echo CHtml::link('Create Variation', array('box/duplicate','id'=>$dateBoxId)); ?></li>
											
											<li><?php echo CHtml::link('Edit Variation', array('box/update','id'=>$dateBoxId)); ?></li>

											<?php if($DateBox->customerCount && isset($dateBoxIds[$pos+1])): ?>
											<li><?php echo CHtml::link('Move 1 Box <span class="glyphicon glyphicon-arrow-right"></span>', array('box/moveBox','from'=>$dateBoxId,'to'=>$dateBoxIds[$pos+1]), array('title'=>'Move a box from this variation')); ?></li>
											<?php endif; ?>

											<?php if(!$DateBox->customerCount && count($dateBoxIds) > 1): ?>	
											<li><?php echo CHtml::link('Delete', array('box/delete','id'=>$dateBoxId)); ?></li>
											<?php endif; ?>
										</ul>
									</div>
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
							<td colspan="<?php echo $dateBoxCount+5 ?>">
								<?php echo CHtml::link($BoxItemsContent->Supplier->name, array('boxItem/create','supplier'=>$BoxItemsContent->supplier_id,'date'=>Yii::app()->request->getQuery('date'))); ?>
								(<strong><?php echo SnapFormat::currency(BoxItem::supplierTotalByDeliveryDate($BoxItemsContent->supplier_id, $SelectedDeliveryDate->id)) ?></strong>)
							</td>
						</tr>
						<?php endif; ?>

						<?php $selectedClass=in_array($selectedItemId,explode(',',$BoxItemsContent->box_item_ids)) ? 'class="selected"' : ''; ?>

						<tr <?php echo $selectedClass ?>>
							<td>
								<?php
									echo CHtml::hiddenField('bc['.$key.'][supplier_id]',$BoxItemsContent->supplier_id);
									echo CHtml::hiddenField('bc['.$key.'][date_id]',$SelectedDeliveryDate->id);
									echo CHtml::hiddenField('bc['.$key.'][supplier_product_id]',$BoxItemsContent->supplier_product_id);
									if(empty($BoxItemsContent->supplier_product_id)) :
										echo CHtml::textField('bc['.$key.'][item_name]',$BoxItemsContent->item_name, array('class' => 'inline-85'));
									else:
										//echo CHtml::link($BoxItemsContent->item_name, array('supplierProduct/update','id'=>$BoxItemsContent->supplier_product_id));
										echo $BoxItemsContent->item_name;
									endif;
									$totalQuantity=BoxItem::totalQuantity($BoxItemsContent->box_item_ids);
									$totalValue=$BoxItemsContent->item_value*$totalQuantity;

									if($BoxItemsContent->SupplierProduct && $BoxItemsContent->item_name != CHtml::value($BoxItemsContent,'SupplierProduct.name')) {
										echo CHtml::link('<i class="fi fi-page-copy"></i>',array('boxItem/copyProductName','id'=>$BoxItemsContent->box_item_id),array('title'=>'Product name should be: ' . CHtml::value($BoxItemsContent,'SupplierProduct.name')));
									}
								?>
							</td>
							<td>
								<?php
									if(!empty($BoxItemsContent->supplier_product_id)) :
										echo CHtml::value($BoxItemsContent, 'SupplierProduct.PackingStation.name');
										echo CHtml::hiddenField('bc['.$key.'][packing_station_id]',CHtml::value($BoxItemsContent, 'SupplierProduct.packing_station_id'));
									else: 
										echo CHtml::dropDownList('bc['.$key.'][packing_station_id]',CHtml::value($BoxItemsContent, 'SupplierProduct.packing_station_id'),CHtml::listData(PackingStation::model()->findAll(), 'id', 'name'));
									endif;
								?>
							</td>
							<td class="itemValue">
								<?php echo CHtml::textField('bc['.$key.'][item_value]',$BoxItemsContent->item_value,array('class'=>'currency')); ?> 
								<?php echo CHtml::dropDownList('bc['.$key.'][item_unit]',$BoxItemsContent->item_unit,SnapUtil::config('boxomatic/itemUnits')); ?>
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
												'class'=>'decimal','min'=>0,'title'=>'Retail: '. SnapFormat::currency($BoxItem->retail_price) .'  Wholesale: '. SnapFormat::currency($BoxItem->wholesale_price) ));
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
								<?php echo SnapFormat::currency($totalValue) ?>
							</td>
							<td class="value">
								<?php echo (float)$totalQuantity ?>
							</td>	
						</tr>
					<?php endforeach;?>
					</tbody>
					<tfoot>
						<tr>
							<td class="total" colspan="3">
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
								<td class="value"><?php echo SnapFormat::currency($value) ?></td>
								<?php endforeach; ?>
							<?php endforeach; ?>
							<td class="value"><strong><?php echo SnapFormat::currency(BoxItem::dateWholesale($SelectedDeliveryDate->id)) ?></strong></td>
							<td></td>
						</tr>
						<tr>
							<td class="total" colspan="3">
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
								<td class="value"><?php echo SnapFormat::currency($DateBox->box_price) ?></td>
								<?php endforeach; ?>
							<?php endforeach; ?>
							<td class="value"><strong><?php echo SnapFormat::currency(BoxItem::dateTarget($SelectedDeliveryDate->id)) ?></strong></td>
							<td></td>
						</tr>
						<tr>
							<td class="total" colspan="3">
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
							<td class="value"><strong><?php echo SnapFormat::currency(BoxItem::dateTarget($SelectedDeliveryDate->id)) ?></strong></td>
							<td></td>
						</tr>
						<tr>
							<td class="total" colspan="3">
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
								<td class="value <?php echo $retail > $DateBox->box_price ? 'red' : '' ?>"><?php echo SnapFormat::currency($retail) ?></td>
								<?php endforeach; ?>
							<?php endforeach; ?>
							<td class="value"><strong><?php echo SnapFormat::currency(BoxItem::dateRetail($SelectedDeliveryDate->id)) ?></strong></td>
							<td></td>
						</tr>

					</tfoot>
				</table>
				<?php $this->endWidget(); ?>
			</div>
			<div id="customers" class="tab-pane">
				<?php $this->beginWidget('bootstrap.widgets.BsPanel'); ?>
					<?php $pageSize=Yii::app()->user->getState('pageSize',10); ?>
					<?php $this->widget('bootstrap.widgets.BsGridView', array(
						'id'=>'user-grid',
						'cssFile' => '', 
						'dataProvider'=>$Customer->search($SelectedDeliveryDate),
						'summaryText'=>'Displaying {start}-{end} of {count} result(s). ' .
						CHtml::dropDownList(
							'pageSize',
							$pageSize,
							array(5=>5,10=>10,20=>20,50=>50,100=>100),
							array('class'=>'change-pageSize')) .
						' rows per page',
						'filter'=>$Customer,
						'columns'=>array(
							'id',
							array(
								'name'=>'full_name_search',
								'value'=>'$data->full_name',
							),
							'email',
							/*
							array(
								'name'=>'balance',
								'value'=>'SnapFormat::currency($data->balance)',
							),
							 */
							/*
							array(
								'name'=>'tag_name_search',
								'filter'=>Tag::getUsedTags('Users'),
								'value'=>'CHtml::value($data,"tag_names")',
							),
							 */
							'order_items',
							'ordered_boxes',
							array(
								'name'=>'dont_want_search',
								//'filter'=>Tag::getUsedTags('Users'),
								'value'=>'CHtml::value($data,"dont_want_items")',
							),
							/*
							array(
								'class'=>'bootstrap.widgets.BsButtonColumn',
								'template'=>'{view}{update}',
								'buttons'=>array(
									'login' => array
									(
										'label'=>'<i class="glyphicon glyphicon-user"></i>',
										'url'=> 'array("user/loginAs","id"=>$data->id)',
										'options'=>array('title'=>'Login As'),
									),
									'reset_password' => array
									(
										'label'=>'<i class="glyphicon glyphicon-lock"></i>',
										'url'=> 'array("user/resetPassword","id"=>$data->id)',
										'options'=>array(
											'confirm'=>'Are you sure you want to reset this user\'s password and send them a welcome email?',
											'title'=>'Reset Password',
										),
									),
								),
							),
							 */
						),
					)); ?>
				<?php $this->endWidget(); ?>
			</div>
		</div>
	</div>
	<?php endif; ?>
	
</div>

<div id="sidebar" class="col-lg-2">
	<div class="sticky">
		<?php $this->beginWidget('bootstrap.widgets.BsPanel', array(
			'title'=>'Menu',
			'contentCssClass'=>'',
			'htmlOptions'=>array(
				'class'=>'panel',
			),
			'type'=>BsHtml::PANEL_TYPE_PRIMARY,
		)); ?>		
		<div class="btn-group btn-group-vertical">
			<?php $this->widget('application.widgets.SnapMenu', array(
				'items'=>$this->menu,
				'htmlOptions'=>array('class'=>'nav nav-stacked'),
			)); ?>			
		</div>
		<?php $this->endWidget(); ?>
	</div>
</div>

<?php $this->endWidget(); ?>

