<?php
	$cs=Yii::app()->clientScript;
	$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/ui-lightness/jquery-ui.css');
	$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/ui.spinner.css');
	$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/ui.selectmenu.css');

	$cs->registerCoreScript('jquery.ui');
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/ui.touch-punch.min.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/ui.selectmenu.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/ui.spinner.min.js', CClientScript::POS_END);
?>
<script type="text/javascript">
	var locCosts = <?php echo json_encode(CHtml::listData(Location::model()->findAll(),'location_id','location_delivery_value')) ?>;
</script>

<h1>Orders</h1>

<div class="tabs">
	<ul>
		<li><a href="#order-in-advance">Order in advance</a></li>
		<li><a href="#orders">Orders</a></li>
	</ul>
	<div id="order-in-advance">		
	<!--	<div class="section info">
			<h2>Box prices</h2>
			<?php foreach($BoxSizes as $BoxSize): ?>
			<div class="row">
				<span class="label"><?php echo $BoxSize->box_size_name; ?></span>
				<span class="value number"><?php echo Yii::app()->snapFormat->currency($BoxSize->box_size_price); ?></span>
			</div>
			<?php endforeach; ?>
		</div>-->

		<div class="section">
			<div class="info">
	<!--			<div class="row">
					<span class="label">Total Orders</span>
					<span class="value number"><?php echo Yii::app()->snapFormat->currency($Customer->fulfilled_order_total); ?></span>
				</div>
				<div class="row">
					<span class="label">Total Payments</span>
					<span class="value number"><?php echo Yii::app()->snapFormat->currency($Customer->totalPayments); ?></span>
				</div>-->
				<div class="row xtotal">
					<span>Credit:</span>
					<span><?php echo Yii::app()->snapFormat->currency($Customer->balance); ?></span>
				</div>
			</div>
		</div>

		<div class="section form allOrders">
			<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'reccuring-customer-order-form',
			'enableAjaxValidation'=>false,
			)); ?>

			<p>
				This will reset your orders for the selected amount of time to the setting chosen here.
				Press the "Clear all orders" button to start over.
			</p>

			<table>
				<thead>
					<th></th>
					<th class="sizes">Sizes</th>
					<th>Boxes</th>
					<th>Delivery</th>
					<th>Total</th>
				</thead>
				<tbody>
					<tr class="date">
						<td colspan="5">
							<?php 
								echo CHtml::hiddenField('delivery_price_', $Customer->Location->location_delivery_value);
								echo $form->dropDownList($Customer,'delivery_location_key',$Customer->getDeliveryLocations(),array('class'=>'deliverySelect')); 
							?>
						</td>
					</tr>
					<tr>
						<td class="button"><span class="btnAdvanced selected" title="Buy more than one box">Advanced</span></td>
						<td>
							<div class="advanced show">
							<?php foreach($BoxSizes as $BoxSize): ?>
								<div>
									<?php echo CHtml::textField('Recurring[bs_' . $BoxSize->box_size_id . ']','0',array('class'=>'number','min'=>0)); ?>
									<?php echo $form->hiddenField($BoxSize,'box_size_price', array('id'=>'bs_value_' . $BoxSize->box_size_id)); ?>	
									<span class="units"><?php echo $BoxSize->box_size_name[0]; ?></span>
								</div>
							<?php endforeach; ?>
							</div>
							<div class="simple">
								<div class="slider"></div>
								<div class="sliderLabels"></div>
							</div>
						</td>
						<?php foreach($BoxSizes as $BoxSize): ?>

						<?php endforeach; ?>
						<td class="boxes"></td>
						<td class="delivery"></td>
						<td class="total"></td>
					</tr>
				</tbody>
			</table>
			<div class="row left">
				<?php echo CHtml::label('Order in advance for', 'months_advance');  ?>
				<?php echo CHtml::dropDownList('months_advance',1,array(1=>'1 Month',3=>'3 Months',6=>'6 Months'));  ?>
			</div>
			<div class="row left">
				<?php echo CHtml::label('Starting from', 'starting_from');  ?>
				<?php echo CHtml::dropDownList('starting_from',1,Week::getFutureWeeks());  ?>
			</div>
			<div class="row left">
				<?php echo CHtml::label('Every', 'every');  ?>
				<?php echo CHtml::dropDownList('every',1,array('week'=>'week','fortnight'=>'fortnight'));  ?>
			</div>

			<div class="clear"></div>
			
			<div class="row buttons">
				<?php echo CHtml::submitButton('Set recurring order', array('name'=>'btn_recurring')); ?>
				<?php echo CHtml::submitButton('Clear all orders', array('name'=>'btn_clear_orders')); ?>
			</div>

			<?php $this->endWidget(); ?>
		</div>
	</div>

	<div id="orders" class="allOrders">
	<!--	<p>Time now: <?php echo date('d-m-y H:i:s') ?></p>-->
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'customer-order-form',
			'enableAjaxValidation'=>false,
		)); 
		//echo $form->hiddenField($Customer->Location, 'location_delivery_value');
		echo CHtml::hiddenField('box_size_count', count($BoxSizes));
		foreach($BoxSizes as $BoxSize){
			echo CHtml::hiddenField('box_size_label_' . $BoxSize->box_size_id, $BoxSize->box_size_name[0], array('class'=>'boxSizeLabel'));
		}
		?>
		<p><?php 
			if(isset($_GET['all']))
				echo CHtml::link('View current orders',array('order','#'=>'orders'));
			else
				echo CHtml::link('View all orders',array('order','all'=>'true','#'=>'orders'));	
		?></p>
		<div class="row buttons">
			<?php echo CHtml::submitButton('Update Orders'); ?>
		</div>
		<table>
			<thead>
				<th></th>
				<th class="sizes">Sizes</th>
				<th>Boxes</th>
				<th>Delivery</th>
				<th>Total</th>
			</thead>
			<tbody>
				<?php 
				$runningBalance=$Customer->balance; //variable to track how many weeks the current balance will last

				foreach($Weeks as $Week): 

					$CustomerBox=null;
					$disabled='';
					$classes='';

					if(strtotime($Week->week_delivery_date) < $deadline && !Yii::app()->user->shadow_id) {
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
					<td colspan="5">
						<strong><?php echo Yii::app()->snapFormat->dayOfYear($Week->week_delivery_date) ?></strong>
						<?php 
							$CustomerWeek=CustomerWeek::model()->findByAttributes(array('week_id'=>$Week->week_id, 'customer_id'=>Yii::app()->user->customer_id));
							if(!$CustomerWeek) {
								$CustomerWeek=new CustomerWeek;
								$CustomerWeek->week_id=$Week->week_id;
								$CustomerWeek->customer_id=Yii::app()->user->customer_id;
								$CustomerWeek->location_id=$Customer->Location->location_id;
								$CustomerWeek->save();
							}
							echo CHtml::hiddenField('delivery_price_'.$CustomerWeek->customer_week_id, $CustomerWeek->Location->location_delivery_value);

							$attribs=array('class'=>'deliverySelect');
							if($disabled)
								$attribs+=array('disabled'=>'disabled');
							echo CHtml::dropDownList('CustWeeks[' . $CustomerWeek->customer_week_id . ']', $CustomerWeek->location_key, $CustomerWeek->Customer->getDeliveryLocations(),$attribs); 
						?>
						<!-- <strong>Order by: </strong><?php echo Yii::app()->snapFormat->dayOfYear($Week->deadline) ?> -->
					</td>
				</tr>
				<tr class="bottom <?php echo $classes ?>">
					<td class="button"><span class="btnAdvanced selected" title="Buy more than one box">Advanced</span></td>
					<td>
						<div class="advanced show">
						<?php foreach($Week->MergedBoxes as $Box):

						$CustomerBox=CustomerBox::findCustomerBox($Week->week_id, $Box->size_id, Yii::app()->user->customer_id);
						$quantity=$CustomerBox ? $CustomerBox->quantity : 0;
						$attribs=array('class'=>'number','min'=>'0');
						if($disabled)
							$attribs+=array('disabled'=>'disabled')
						?>
							<div>
								<?php echo CHtml::textField('Orders[' . $Box->box_id . ']', $quantity, $attribs) ?>
								<?php echo CHtml::hiddenField('box_value', $Box->box_price,  array('id'=>'b_value_' . $Box->size_id)); ?>	
								<span class="units"><?php echo $Box->BoxSize->box_size_name[0]; ?></span>
							</div>
						<?php endforeach; ?>
						</div>
						<div class="simple">
							<div class="slider"></div>
							<div class="sliderLabels"></div>
						</div>
					</td>
					<td class="bottom price boxes">
						<?php echo Yii::app()->snapFormat->currency($Customer->totalBoxesByWeek($Week->week_id)) ?>
					</td>
					<td class="bottom price delivery">
					<?php echo Yii::app()->snapFormat->currency($Customer->totalDeliveryByWeek($Week->week_id)); ?>
					</td>
					<td class="bottom price total">
						<strong><?php echo Yii::app()->snapFormat->currency($Customer->totalByWeek($Week->week_id)) ?></strong>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="row buttons">
			<?php echo CHtml::submitButton('Update Orders'); ?>
		</div>
		<p><?php 
			echo CHtml::link('Load more weeks',array('order','show'=>$show+4,'#'=>'orders'));	
		?></p>
		<?php $this->endWidget(); ?>
	</div>
	
</div><!-- .tabs -->