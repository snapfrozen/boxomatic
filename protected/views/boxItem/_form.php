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
					'name'=>'Grower.grower_name',
					'type'=>'raw',
					'value'=>'CHtml::link($data->Grower->grower_name,array_merge(array("boxItem/create","item"=>$data->item_id,"week"=>Yii::app()->request->getQuery("week"))))',
					'cssClassExpression'=>'"grower-".$data->Grower->grower_id',
				),
				'item_name',
				'item_value',
				array( 'name'=>'item_available_from', 'value'=>'Yii::app()->snapFormat->getMonthName($data->item_available_from)' ),
				array( 'name'=>'item_available_to', 'value'=>'Yii::app()->snapFormat->getMonthName($data->item_available_to)' ),
			),
		)); ?>
		
	</div>
	
	<div class="clear"></div>
	
	<div class="section">
		<h2>Boxes</h2>
		<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'box-item-form',
		'enableAjaxValidation'=>false,
		)); ?>	
		<div id="current-boxes">
		<?php if($SelectedWeek): ?>
			<table>
				<thead>
					<tr>
						<th class="growerName">Grower Name</th>
						<th>Item</th>
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
						<th>Value</th>
					</tr>
				</thead>
				<tbody>
					<?php if($NewItem): ?>
					<tr class="selected">
						<td>
							<?php echo $NewItem->Grower->grower_name; ?>
							<?php
								echo CHtml::hiddenField('bc[-1][grower_id]',$NewItem->grower_id);
								echo CHtml::hiddenField('bc[-1][week_id]',$SelectedWeek->week_id);
							?>
						</td>
						<td><?php echo CHtml::textField('bc[-1][item_name]',$NewItem->item_name); ?></td>
						<td class="itemValue">
							<?php echo CHtml::textField('bc[-1][item_value]',$NewItem->item_value,array('class'=>'currency')); ?>
							<?php echo CHtml::dropDownList('bc[-1][item_unit]',$NewItem->item_unit,Yii::app()->params['itemUnits']); ?>
						</td>
						<?php foreach($WeekBoxes as $key=>$Box): ?>
							<td><?php 
								echo CHtml::textField('bc[-1][BoxItem][' .$key .'][item_quantity]', 0, array('class'=>'decimal','min'=>0)); 
								echo CHtml::hiddenField('bc[-1][BoxItem][' .$key .'][box_id]', $Box->box_id);
							?></td>
						<?php endforeach; ?>
						<td class="value"></td>
					</tr>
					<?php endif; ?>
					<?php foreach($SelectedWeek->BoxItems as $key=>$WeekItem): 
						$aBoxItemIds=array();
					?>
					<tr>
						<td>
							<?php echo $WeekItem->Grower->grower_name; ?>
							<?php
								echo CHtml::hiddenField('bc['.$key.'][grower_id]',$WeekItem->grower_id);
								echo CHtml::hiddenField('bc['.$key.'][week_id]',$SelectedWeek->week_id);
							?>
						</td>
						<td><?php echo CHtml::textField('bc['.$key.'][item_name]',$WeekItem->item_name); ?></td>
						<td class="itemValue">
							<?php echo CHtml::textField('bc['.$key.'][item_value]',$WeekItem->item_value,array('class'=>'currency')); ?> 
							<?php echo CHtml::dropDownList('bc['.$key.'][item_unit]',$WeekItem->item_unit,Yii::app()->params['itemUnits']); ?>
						</td>
						<?php foreach($WeekBoxes as $key2=>$Box): 
							
							$BoxItem=BoxItem::model()->with('Box')->find(
								'item_name=:itemName AND 
								grower_id=:growerId AND 
								item_unit=:itemUnit AND 
								item_value=:itemValue AND 
								Box.week_id=:weekId AND 
								Box.size_id=:sizeId', 
								array (
									':itemName'=>$WeekItem->item_name,
									':growerId'=>$WeekItem->grower_id,
									':itemUnit'=>$WeekItem->item_unit,
									':itemValue'=>$WeekItem->item_value,
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
									$aBoxItemIds[]=$BoxItem->box_item_id;
								else:
									echo CHtml::textField('bc['.$key.'][BoxItem]['.$key2.'][item_quantity]', 0, array('class'=>'decimal','min'=>0));
									echo CHtml::hiddenField('bc['.$key.'][BoxItem]['.$key2.'][box_id]', $Box->box_id);
								endif;
							?></td>
						<?php endforeach; ?>
						<td class="value">
							<?php echo Yii::app()->snapFormat->currency(BoxItem::itemTotal(implode(',',$aBoxItemIds))) ?>
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
						foreach($WeekBoxes as $WeekBox): 
							$value=$SelectedWeek->with(array('totalBoxValue'=>array('params'=>array(':sizeId'=>$WeekBox->size_id))))->findByPk($SelectedWeek->week_id)->totalBoxValue;
							$totalValue+=$value;
						?>
						<td><?php echo Yii::app()->snapFormat->currency($value) ?></td>
						<?php endforeach; ?>
						<td><strong><?php echo Yii::app()->snapFormat->currency($totalValue) ?></strong></td>
					</tr>
					<tr>
						<td class="total" colspan="3">
							Box Retail:
						</td>
						<?php 
						$totalRetal=0;
						foreach($WeekBoxes as $WeekBox): 
							$value=$SelectedWeek->with(array('totalBoxValue'=>array('params'=>array(':sizeId'=>$WeekBox->size_id))))->findByPk($SelectedWeek->week_id)->totalBoxValue;	
							$retail=$value+($value*($WeekBox->BoxSize->box_size_markup/100));
							$totalRetal+=$retail;
						?>
						<td><?php echo Yii::app()->snapFormat->currency($retail) ?></td>
						<?php endforeach; ?>
						<td><strong><?php echo Yii::app()->snapFormat->currency($totalRetal) ?></strong></td>
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