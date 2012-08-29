<?php
	$cs=Yii::app()->clientScript;
	$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/redmond/jquery-ui.css');
	$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/ui.spinner.css');
	
	$cs->registerCoreScript('jquery.ui');
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/ui.touch-punch.min.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/ui.datepicker.min.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/ui.spinner.min.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/boxitem/_form.js',CClientScript::POS_END);
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
		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'grower-item-grid',
			'dataProvider'=>$GrowerItems->search(),
			'filter'=>$GrowerItems,
			'rowCssClassExpression'=>'$data->item_id==Yii::app()->request->getQuery("item") ? "active" : null',
			'selectableRows'=>0,
			//'selectionChanged'=>'changeBoxItem',
			'columns'=>array(
				array(
					'name'=>'grower_search',
					'type'=>'raw',
					'value'=>'CHtml::link($data->Grower->grower_name,array_merge(array("boxItem/create","item"=>$data->item_id,"week"=>Yii::app()->request->getQuery("week"))))',
					'cssClassExpression'=>'"grower-".$data->Grower->grower_id',
//					'filter'=>$GrowerItems
				),
				'item_name',
				'item_value',
				array( 
					'name'=>'item_available_from', 
					'value'=>'Yii::app()->snapFormat->getMonthName($data->item_available_from)',
					'filter'=>Yii::app()->params["months"],
				),
				array( 
					'name'=>'item_available_to', 
					'value'=>'Yii::app()->snapFormat->getMonthName($data->item_available_to)',
					'filter'=>Yii::app()->params["months"],
				),
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
			<table>
				<thead>
					<tr>
						<th class="growerName">Items</th>
						<th>Value</th>
						<?php 
							foreach($WeekBoxes as $WeekBox): 
							$WeekBox->size_id;
						?>
						<th>
							<?php echo $WeekBox->customerCount ?><br />
							<?php echo $WeekBox->BoxSize->box_size_name ?>
						</th>
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
							<td colspan="7">
								<?php echo CHtml::link($WeekItemContent->Grower->grower_name, array('boxItem/create','grower'=>$WeekItemContent->grower_id,'week'=>Yii::app()->request->getQuery('week'))); ?>
								(<strong><?php echo Yii::app()->snapFormat->currency(BoxItem::growerTotalByWeek($WeekItemContent->grower_id, $SelectedWeek->week_id)) ?></strong>)
							</td>
						</tr>
					<?php endif; ?>
						
					<?php $selectedClass=in_array($selectedItemId,explode(',',$WeekItemContent->box_item_ids)) ? 'class="selected"' : ''; ?>
						
					<tr <?php echo $selectedClass ?>>
						<td>
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
						foreach($WeekBoxes as $key2=>$Box): 
							
							$BoxItem=BoxItem::model()->with('Box')->find(
								'item_name=:itemName AND 
								grower_id=:growerId AND 
								item_unit=:itemUnit AND 
								item_value=:itemValue AND 
								Box.week_id=:weekId AND 
								Box.size_id=:sizeId', 
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
									echo CHtml::textField('bc['.$key.'][BoxItem]['.$key2.'][item_quantity]', $BoxItem->item_quantity, array('class'=>'decimal','min'=>0));
									echo CHtml::hiddenField('bc['.$key.'][BoxItem]['.$key2.'][box_item_id]', $BoxItem->box_item_id);
									echo CHtml::hiddenField('bc['.$key.'][BoxItem]['.$key2.'][box_id]', $Box->box_id);
								else:
									echo CHtml::textField('bc['.$key.'][BoxItem]['.$key2.'][item_quantity]', 0, array('class'=>'decimal','min'=>0));
									echo CHtml::hiddenField('bc['.$key.'][BoxItem]['.$key2.'][box_id]', $Box->box_id);
								endif;
							?></td>
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
						foreach($WeekBoxes as $WeekBox): 
							$value=$SelectedWeek->with(array('totalBoxValue'=>array('params'=>array(':sizeId'=>$WeekBox->size_id))))->findByPk($SelectedWeek->week_id)->totalBoxValue;
							$totalValue+=$value;
						?>
						<td class="value"><?php echo Yii::app()->snapFormat->currency($value) ?></td>
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
						foreach($WeekBoxes as $WeekBox): 	
							$totalRetal+=$WeekBox->box_price;
						?>
						<td class="value"><?php echo Yii::app()->snapFormat->currency($WeekBox->box_price) ?></td>
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
						foreach($WeekBoxes as $WeekBox): 
							$value=$SelectedWeek->with(array('totalBoxValue'=>array('params'=>array(':sizeId'=>$WeekBox->size_id))))->findByPk($SelectedWeek->week_id)->totalBoxValue;	
							$retail=$value+($value*($WeekBox->BoxSize->box_size_markup/100));
							$totalRetal+=$retail;
						?>
						<td class="value <?php echo $retail > $WeekBox->box_price ? 'red' : '' ?>"><?php echo Yii::app()->snapFormat->currency($retail) ?></td>
						<?php endforeach; ?>
						<td class="value"><strong><?php echo Yii::app()->snapFormat->currency(BoxItem::weekRetail($SelectedWeek->week_id)) ?></strong></td>
						<td></td>
					</tr>
					
				</tfoot>
			</table>
		<?php endif; ?>
		</div>
		<?php echo CHtml::submitButton('Update Boxes'); ?>
		<?php $this->endWidget(); ?>
	</div>

	<div class="section half">

	</div>

</div><!-- form -->