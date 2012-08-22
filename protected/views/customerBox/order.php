<?php
	$cs=Yii::app()->clientScript;
	$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/redmond/jquery-ui.css');
	$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/ui.spinner.css');
	$cs->registerCoreScript('jquery.ui');
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/ui.touch-punch.min.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/ui.spinner.min.js', CClientScript::POS_END);
?>
<div id="allOrders" class="half">
	<p>Time now: <?php echo date('d-m-y H:i:s') ?></p>
	<h1>Orders</h1>
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'customer-order-form',
		'enableAjaxValidation'=>false,
	)); 
	echo $form->hiddenField($Customer->Location, 'location_delivery_value');
	echo CHtml::hiddenField('box_size_count', count($BoxSizes));
	?>
	<p><?php 
		if(isset($_GET['all']))
			echo CHtml::link('View current orders',array('order'));
		else
			echo CHtml::link('View all orders',array('order','all'=>'true'));	
	?></p>
	<div class="row buttons">
		<?php echo CHtml::submitButton('Update Orders'); ?>
	</div>
	<table>
		<thead>
			<th></th>
			<?php foreach($BoxSizes as $BoxSize): ?>
			<th class="center"><?php echo $BoxSize->box_size_name[0]; //Get first letter ?></th>
			<?php endforeach; ?>
			<th>Boxes</th>
			<th>Delivery</th>
			<th>Total</th>
		</thead>
		<tbody>
			<?php 
			$runningBalance=$Customer->totalPayments - $Customer->fulfilled_order_total; //variable to track how many weeks the current balance will last

			foreach($Weeks as $Week): 
				
				$CustomerBox=null;
				$disabled='';
				$classes='';
				
				if(strtotime($Week->week_delivery_date) < $deadline) {
					$disabled='disabled';
					$classes.=' pastDeadline';
				} else {
					//only subtract the running balance if it hasn't already been subtracted from their credit (i.e. past the deadline)
					$runningBalance-=$Customer->totalByWeek($Week->week_id);
				}

				if($runningBalance >= 0) {
					$classes.=' enoughCredit';
				} else if ($runningBalance < 0  && time() > strtotime('-14 days', strtotime($Week->week_delivery_date))) {
					$classes.=' insufficientCredit';
				}
			
			?>
			<tr class="date <?php echo $classes ?>">
				<td class="button"><span class="btnAdvanced selected">Adv</span></td>
				<td colspan="<?php echo sizeof($BoxSizes)+3 ?>">
					<strong>Delivery: </strong><?php echo Yii::app()->snapFormat->dayOfYear($Week->week_delivery_date) ?> 
					<?php 
					
						$TmpBox = $Week->Boxes[0];
						$CustBox = CustomerBox::model()->findByAttributes(array('box_id'=>$TmpBox->box_id, 'customer_id'=>Yii::app()->user->customer_id));

					?>
					<!-- <strong>Order by: </strong><?php echo Yii::app()->snapFormat->dayOfYear($Week->deadline) ?> -->
				</td>
			</tr>
			<tr class="show <?php echo $classes ?>">
				<td class="advanced"></td>
				<?php foreach($Week->Boxes as $Box):
				$CustomerBox=CustomerBox::model()->findByAttributes(array('box_id'=>$Box->box_id, 'customer_id'=>Yii::app()->user->customer_id));
				$quantity=$CustomerBox ? $CustomerBox->quantity : 0;
				$attribs=array('class'=>'number','min'=>'0');
				if($disabled)
					$attribs+=array('disabled'=>'disabled')
				?>
				<td class="advanced">
					<span class="content">
						<?php echo CHtml::textField('Orders[b_' . $Box->box_id . ']', $quantity, $attribs) ?>
						<?php echo CHtml::hiddenField('box_value', $Box->box_price,  array('id'=>'b_value_' . $Box->box_id)); ?>	
					</span>
				</td>
				<?php endforeach; ?>
				<td class="bottom price boxes" rowspan="2">
					<?php echo Yii::app()->snapFormat->currency($Customer->totalBoxesByWeek($Week->week_id)) ?>
				</td>
				<td class="bottom price delivery" rowspan="2">
				<?php echo Yii::app()->snapFormat->currency($Customer->totalDeliveryByWeek($Week->week_id)); ?>
				</td>
				<td class="bottom price total" rowspan="2">
					<strong><?php echo Yii::app()->snapFormat->currency($Customer->totalByWeek($Week->week_id)) ?></strong>
				</td>
			</tr>
			<tr class="selector bottom <?php echo $classes ?>">
				<td colspan="<?php echo count($Week->Boxes) + 1?>">
					<div class="slider"></div>
					<div class="sliderLabels">
					</div>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton('Update Orders'); ?>
	</div>
	<p><?php 
		echo CHtml::link('Load more weeks',array('order','show'=>$show+4));	
	?></p>
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
			<span class="label">Delivery to: <?php echo CHtml::link($Customer->Location->location_name,array('user/update','id'=>Yii::app()->user->id)) ?></span>
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
				<span class="label">Credit	</span>
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
				<?php echo CHtml::textField('Recurring[bs_' . $BoxSize->box_size_id . ']','0',array('class'=>'number','min'=>0)); ?>
				<?php echo $form->hiddenField($BoxSize,'box_size_price', array('id'=>'bs_value_' . $BoxSize->box_size_id)); ?>	
			</td>
			<?php endforeach; ?>
			<td class="recBoxes"></td>
			<td class="recDelivery"></td>
			<td class="recTotal"></td>
		</table>
		
		<div class="row buttons">
			<?php echo CHtml::submitButton('Set recurring order', array('name'=>'btn_recurring')); ?>
			<?php echo CHtml::submitButton('Clear all orders', array('name'=>'btn_clear_orders')); ?>
		</div>
		
		<?php $this->endWidget(); ?>
	</div>
</div>