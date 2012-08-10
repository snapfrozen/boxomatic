<h1>Orders</h1>

<div class="info">
	<div class="half">
		<h2>Box prices</h2>
		<?php foreach($BoxSizes as $BoxSize): ?>
		<div class="row">
			<span class="label"><?php echo $BoxSize->box_size_name; ?></span>
			<span class="value"><?php echo Yii::app()->snapFormat->currency($BoxSize->box_size_price); ?></span>
		</div>
		<?php endforeach; ?>
		<div class="row">
			<span class="label">Delivery</span>
			<span class="value"><?php echo Yii::app()->snapFormat->currency($Customer->Location->location_delivery_value); ?> (per box)</span>
		</div>
	</div>
	<div class="clear"></div>
</div>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'customer-order-form',
	'enableAjaxValidation'=>false,
)); ?>

<div id="allOrders">
	<div class="row buttons">
		<?php echo CHtml::submitButton('Update Orders'); ?>
	</div>
	<table>
		<thead>
			<?php foreach($BoxSizes as $BoxSize): ?>
			<th><?php echo $BoxSize->box_size_name; ?></th>
			<?php endforeach; ?>
			<th>Box</th>
			<th>Delivery</th>
			<th>Total</th>
		</thead>
		<tbody>
			<?php foreach($Weeks as $Week): 
			$CustomerBox=null;
			?>
			<tr class="date">				
				<td colspan="<?php echo sizeof($BoxSizes)+3 ?>"><?php echo Yii::app()->snapFormat->dayOfYear($Week->week_delivery_date) ?></td>
			</tr>
			<tr>
				<?php foreach($Week->Boxes as $Box): 
				$CustomerBox=CustomerBox::model()->findByAttributes(array('box_id'=>$Box->box_id,'customer_id'=>Yii::app()->user->customer_id));
				$quantity=$CustomerBox ? $CustomerBox->quantity : 0;
				?>
				<td>
					<?php echo CHtml::textField('Orders[b_' . $Box->box_id . ']', $quantity, array('class'=>'number')) ?>
				</td>
				<?php endforeach; ?>
				<td>
					<?php echo Yii::app()->snapFormat->currency($Customer->totalBoxesByWeek($Week->week_id)) ?>
				</td>
				<td>
					<?php echo Yii::app()->snapFormat->currency($Customer->totalDeliveryByWeek($Week->week_id)) ?>
				</td>
				<td>
					<strong><?php echo Yii::app()->snapFormat->currency($Customer->totalByWeek($Week->week_id)) ?></strong>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton('Update Orders'); ?>
	</div>
</div>

<?php $this->endWidget(); ?>
