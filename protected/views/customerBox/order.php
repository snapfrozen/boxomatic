
<?php
	/*$$cs=Yii::app()->clientScript;
	$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/ui-lightness/jquery-ui.css');
	$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/ui.spinner.css');
	$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/ui.selectmenu.css');

	$cs->registerCoreScript('jquery.ui');
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/ui.touch-punch.min.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/ui.selectmenu.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/ui.spinner.min.js', CClientScript::POS_END);*/

	$cs=Yii::app()->clientScript;
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/pages/customBox/order.js', CClientScript::POS_END);
?>
<script type="text/javascript">
	var locCosts = <?php echo json_encode(CHtml::listData(Location::model()->findAll(),'location_id','location_delivery_value')) ?>;
</script>

<div class="row">
	<div class="large-8 columns">
		<h2>Order Summary</h2>

		<div class="section-container auto" data-section>
			<section>
				<p class="title" data-section-title><a href="#panel1">Orders</a></p>
				<div class="content" data-section-content>
					<div class="row">
						<div class="large-12 columns">
							<?php
								if(isset($_GET['all']))
									echo CHtml::link('View current orders', array('order','#'=>'orders'), array('class' => 'button small'));
								else
									echo CHtml::link('View all orders',array('order','all'=>'true','#'=>'orders'), array('class' => 'button small'));	
							?>
						</div>

						<?php 

						$form=$this->beginWidget('CActiveForm', array(
							'id'=>'customer-order-form', 
							// 'htmlOptions' => array('class' => 'custom'), 	
							'enableAjaxValidation'=>false,
						)); 

						echo CHtml::hiddenField('box_size_count', count($BoxSizes));

						foreach($BoxSizes as $BoxSize){
							echo CHtml::hiddenField('box_size_label_' . $BoxSize->id, $BoxSize->box_size_name, array('class'=>'boxSizeLabel'));
						}

						?>

						<div class="large-12 columns">
							<ul class="no-bullet">
								
								<?php $runningBalance=$Customer->balance; ?>
								
								<?php foreach($DeliveryDates as $DeliveryDate): ?>

								<?php

									$CustomerBox=null;
									$disabled='';
									$classes='';

									if(strtotime($DeliveryDate->date) < $deadline && !Yii::app()->user->shadow_id || $DeliveryDate->disabled) {
										$disabled='disabled';
										$classes.=' pastDeadline';
									} else {
										//only subtract the running balance if it hasn't already been subtracted from their credit (i.e. past the deadline)
										$runningBalance-=$Customer->totalByDeliveryDate($DeliveryDate->id);
									}

									if($runningBalance >= 0) {
										$classes.=' enoughCredit';
									} else if ($runningBalance < 0  && time() > strtotime('-14 days', strtotime($DeliveryDate->date))) {
										$classes.=' insufficientCredit';
									}

								?>

								<li class="<?php echo $classes; ?>">
									<div class="row">
										<div class="large-4 columns">
											<p><?php echo Yii::app()->snapFormat->dayOfYear($DeliveryDate->date) ?></p>
											<?php 
												$CustomerDeliveryDate=CustomerDeliveryDate::model()->findByAttributes(array('delivery_date_id'=>$DeliveryDate->id, 'customer_id'=>Yii::app()->user->customer_id));
												if(!$CustomerDeliveryDate) {
													$CustomerDeliveryDate=new CustomerDeliveryDate;
													$CustomerDeliveryDate->delivery_date_id=$DeliveryDate->id;
													$CustomerDeliveryDate->customer_id=Yii::app()->user->customer_id;
													$CustomerDeliveryDate->location_id=$Customer->Location->location_id;
													$CustomerDeliveryDate->save();
												}
												echo CHtml::hiddenField('delivery_price_'.$CustomerDeliveryDate->id, $CustomerDeliveryDate->Location->location_delivery_value);
											?>
										</div>

										<div class="large-8 columns">
											<?php
												$attribs=array('class'=>'deliverySelect');
												if($disabled)
													$attribs+=array('disabled'=>'disabled');
												echo CHtml::dropDownList('CustDeliveryDates[' . $CustomerDeliveryDate->id . ']', $CustomerDeliveryDate->location_key, $CustomerDeliveryDate->Customer->getDeliveryLocations(),$attribs); 
											?>
										</div>
									</div>

									<div class="row">
										<div class="large-6 columns">
											<?php 

											foreach($DeliveryDate->MergedBoxes as $Box):

											$CustomerBox=CustomerBox::findCustomerBox($DeliveryDate->id, $Box->size_id, Yii::app()->user->customer_id);
											$quantity=$CustomerBox ? $CustomerBox->quantity : 0;

											$attribs = array('min'=>'0');
											if($disabled)
												$attribs += array('disabled'=>'disabled');
											else
												$attribs += array('class' => 'numeric'); 
											?>
											
											<?php if($Box->BoxSize->box_size_name): ?>

												<div class="large-4 columns">
													<label for=""><?php echo $Box->BoxSize->box_size_name; ?></label>
													<!-- <input type="text" class="<?php if(!$disabled): ?>numeric<?php endif; ?>" name="Orders[<?php echo $Box->box_id; ?>]" <?php if($disabled): ?>disabled<?php endif; ?>> -->
													<!-- <input id="b_value_<?php echo $Box->size_id; ?>" type="hidden" class="box_value" name="box_value" value="<?php echo $Box->box_price; ?>"> -->
													<?php echo CHtml::textField('Orders[' . $Box->box_id . ']', $quantity, $attribs); ?>
													<?php echo CHtml::hiddenField('box_value', $Box->box_price,  array('id'=>'b_value_' . $Box->size_id)); ?>	
												</div>
											
											<?php endif; ?>

											<?php endforeach; ?>

										</div>

										<div class="large-6 columns">
											<table>
												<thead>
													<tr>
														<th>Boxes</th>
														<th>Extras</th>
														<th>Delivery</th>
														<th>Total</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class='boxes'><?php echo Yii::app()->snapFormat->currency($Customer->totalBoxesByDeliveryDate($DeliveryDate->id)) ?></td>
														<td class='extras'>?</td>
														<td class='delivery'><?php echo Yii::app()->snapFormat->currency($Customer->totalDeliveryByDeliveryDate($DeliveryDate->id)); ?></td>
														<td class='total'>
															<strong><?php echo Yii::app()->snapFormat->currency($Customer->totalByDeliveryDate($DeliveryDate->id)) ?></strong>
															<?php if(Yii::app()->user->shadow_id): ?>
															<?php echo CHtml::link('update',array('deliveryDate/updateCustomerBoxes','date_id'=>$DeliveryDate->id,'cust_id'=>$Customer->customer_id), array('class' => 'button small')); ?>
															<?php endif; ?>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
										
										<div class="large-12 columns">
											<?php echo CHtml::link('Update Extras',array('customerDeliveryDateItem/order','date'=>$DeliveryDate->id),array('class'=>'button small')) ?>
										</div>
										
									</div>
								</li>
								<?php endforeach; ?>
							</ul>

						</div>
						<div class="large-12 columns">
							<div class="large-6 columns">
								<?php echo CHtml::link('Load more dates',array('order','show'=>$show+4,'#'=>'orders'), array('class' => 'button')); ?>
							</div>
							<div class="large-6 columns">
								<div class="right">
									<?php echo CHtml::submitButton('Update Orders', array('class' => 'button')); ?>
								</div>
							</div>
						</div>

						<?php $this->endWidget(); ?>

					</div>

					
				</div>
			</section>
			<section>
				<p class="title" data-section-title><a href="#panel2">Order in Advance</a></p>
				<div class="content" data-section-content>

					<h6 class='mb'>This will reset your orders for the selected amount of time to the setting chosen here. Press the "Clear all orders" button to start over.</h6>

					<?php $form=$this->beginWidget('CActiveForm', array(
					'id'=>'reccuring-customer-order-form', 
					// 'htmlOptions' => array('class' => 'custom'), 
					'enableAjaxValidation'=>false,
					)); ?>

					
					<?php 
						echo CHtml::label('Deliver to', 'delivery_price_');
						echo $form->dropDownList($Customer,'delivery_location_key',$Customer->getDeliveryLocations(),array('class'=>'deliverySelect')); 
					?>
					
					<?php echo CHtml::link('Add new delivery location',array('customerLocation/create','custId'=>$Customer->customer_id,'order'=>1), array('class' => 'button small'))?>
					
					<?php echo CHtml::hiddenField('delivery_price_', $Customer->Location->location_delivery_value); ?>

					<table style='width:100%;'>
						<thead>
							<tr>
								<th style='width:35%'>Sizes</th>
								<th>Boxes</th>
								<th>Delivery</th>
								<th>Total</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<div class="row">
										<?php foreach($BoxSizes as $boxsize): ?>
										<?php if($boxsize->box_size_name != ''): ?>
										<div class="large-4 columns">
											<label for=""><?php echo $boxsize->box_size_name; ?></label>
											<?php echo CHtml::textField('Recurring[bs_' . $boxsize->id . ']','0',array('class'=>'number numeric','min'=>0)); ?>
											<?php echo $form->hiddenField($boxsize,'box_size_price', array('id'=>'bs_value_' . $boxsize->id)); ?>	
										</div>
										<?php endif; ?>
										<?php endforeach; ?>
									</div>
								</td>
								<td class='box'>$0.00</td>
								<td>$0.00</td>
								<td class='total'><strong>$0.00</strong></td>
							</tr>
						</tbody>
					</table>

					<div class="row">
						<div class="large-4 columns">
							<?php echo CHtml::label('Order in advance for', 'months_advance');  ?>
							<?php echo CHtml::dropDownList('months_advance',1,array(1=>'1 Month',3=>'3 Months',6=>'6 Months'));  ?>
						</div>
						<div class="large-4 columns">
							<?php echo CHtml::label('Starting from', 'starting_from');  ?>
							<?php echo CHtml::dropDownList('starting_from',1,DeliveryDate::getFutureDeliveryDates());  ?>
							
						</div>
						<div class="large-4 columns">
							<?php echo CHtml::label('Every', 'every');  ?>
							<?php echo CHtml::dropDownList('every',1,array('week'=>'week','fortnight'=>'fortnight'));  ?>
						</div>
					</div>

					<div class="row">
						<div class="large-12 columns">
							<div class="right">
								<?php echo CHtml::submitButton('Set recurring order', array('name'=>'btn_recurring','class'=>'button')); ?>
							</div>
						</div>
					</div>

					<?php $this->endWidget(); ?>

				</div>
			</section>
		</div>
	</div>
	<div class="large-4 columns">
		<h2>Transactions</h2>
		<?php $this->widget('zii.widgets.CListView', array(
			'dataProvider'=>new CActiveDataProvider('CustomerPayment',array(
				'criteria'=>array(
					'condition'=>'customer_id='.$Customer->customer_id,
					'order'=>'payment_date DESC',
					'limit'=>5,
				),
				'pagination'=>false,
			)),
			'itemView'=>'../customerPayment/_view',
			'summaryText'=>false,
		)); ?>
	</div>
</div>
