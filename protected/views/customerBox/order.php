<h1>Orders</h1>

<div id="allOrders" class="half">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'customer-order-form',
		'enableAjaxValidation'=>false,
	)); ?>
	<div class="row buttons">
		<?php echo CHtml::submitButton('Update Orders'); ?>
	</div>
	<table>
		<thead>
			<?php foreach($BoxSizes as $BoxSize): ?>
			<th><?php echo $BoxSize->box_size_name; ?></th>
			<?php endforeach; ?>
			<th>Boxes</th>
			<th>Delivery</th>
			<th>Total</th>
		</thead>
		<tbody>
			<?php 
			$runningBalance=$Customer->fulfilled_order_total; //variable to track how many weeks the current balance will last

			foreach($Weeks as $Week): 
				
			$CustomerBox=null;
			$runningBalance-=$Customer->totalByWeek($Week->week_id);
			
			$disabled='';
			$classes='';
			if(strtotime($Week->week_delivery_date) < $deadline) {
				$disabled='disabled';
				$classes.=' pastDeadline';
			}

			if($runningBalance > 0) {
				$classes.=' enoughCredit';
			}
			
			?>
			<tr class="date <?php echo $classes ?>">				
				<td colspan="<?php echo sizeof($BoxSizes)+3 ?>">
					<?php echo Yii::app()->snapFormat->dayOfYear($Week->week_delivery_date) ?>
					<?php if(!empty($disabled)) echo ' - Past delivery deadline' ?>
				</td>
			</tr>
			<tr class="<?php echo $classes ?>">
				<?php foreach($Week->Boxes as $Box): 
				$CustomerBox=CustomerBox::model()->findByAttributes(array('box_id'=>$Box->box_id, 'customer_id'=>Yii::app()->user->customer_id));
				$quantity=$CustomerBox ? $CustomerBox->quantity : 0;
				?>
				<td>
					<?php echo CHtml::textField('Orders[b_' . $Box->box_id . ']', $quantity, array('class'=>'number',$disabled=>$disabled)) ?>
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
	<?php $this->endWidget(); ?>
</div>

<div class="info half">
	
	<div class="section">
		<h2>Box prices</h2>
		<?php foreach($BoxSizes as $BoxSize): ?>
		<div class="row">
			<span class="label"><?php echo $BoxSize->box_size_name; ?></span>
			<span class="value number"><?php echo Yii::app()->snapFormat->currency($BoxSize->box_size_price); ?></span>
		</div>
		<?php endforeach; ?>
		<div class="row">
			<span class="label">Delivery (per box)</span>
			<span class="value number"><?php echo Yii::app()->snapFormat->currency($Customer->Location->location_delivery_value); ?></span>
		</div>
	</div>
	
	<div class="section">
		<h2>Orders and Payments</h2>
		<div class="info">
			<div class="row">
				<span class="label">Total Orders</span>
				<span class="value number"><?php echo Yii::app()->snapFormat->currency($Customer->fulfilled_order_total); ?></span>
			</div>
			<div class="row">
				<span class="label">Total Payments</span>
				<span class="value number"><?php echo Yii::app()->snapFormat->currency($Customer->totalPayments); ?></span>
			</div>
			<div class="row total">
				<span class="label">Credit</span>
				<span class="value number"><?php echo Yii::app()->snapFormat->currency($Customer->balance); ?></span>
			</div>
		</div>
	</div>
	
	<div class="section form">
		<h2>Recurring Order</h2>
		<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'reccuring-customer-order-form',
		'enableAjaxValidation'=>false,
		)); ?>

		<p>
			All future orders and will be set to your preference below. Set to 0 for no recurring order.
			Any entry already added will not be affected. Press the "Clear all orders" button to start over.
		</p>
		
		<table>
			<thead>
				<?php foreach($BoxSizes as $BoxSize): ?>
				<th><?php echo $BoxSize->box_size_name; ?></th>
				<?php endforeach; ?>
				<th>Boxes</th>
				<th>Delivery</th>
				<th>Total</th>
			</thead>
			<tbody>
			<?php foreach($BoxSizes as $BoxSize): ?>
			<td>
				<?php echo CHtml::textField('Recurring[bs_' . $BoxSize->box_size_id . ']','',array('class'=>'number')); ?>
			</td>
			<?php endforeach; ?>
		</table>
		
		<div class="row buttons">
			<?php echo CHtml::submitButton('Set recurring order', array('name'=>'btn_recurring')); ?>
			<?php echo CHtml::submitButton('Clear all orders', array('name'=>'btn_clear_orders')); ?>
		</div>
		
		<?php $this->endWidget(); ?>
	</div>
</div>