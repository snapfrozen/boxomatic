<?php
	$cs=Yii::app()->clientScript;
	$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/ui-lightness/jquery-ui.css');
	$cs->registerCoreScript('jquery.ui');
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/ui.datepicker.min.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/chosen.jquery.min.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/customerdeliverydateitem/_form.js',CClientScript::POS_END);
?>
<?php if($Customer): ?>
<div class="secondaryNav row">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'recurring-form',
		'enableAjaxValidation'=>false,
	)); ?>
	<div class="large-4 columns location">
		<h4>Location:</h4>
		<?php //echo CHtml::ActiveDropDownList($CustDeliveryDate,'location_id',Location::getDeliveryAndPickupList()); ?>
		<?php echo CHtml::ActiveDropDownList($CustDeliveryDate,'delivery_location_key',$Customer->getDeliveryLocations()); ?>
	</div>
	
	<div class="large-4 columns end">
		<script type="text/javascript">
			var curUrl="<?php echo $this->createUrl('extras/order'); ?>";
			var curUrlWithId="<?php echo $this->createUrl('extras/order',array('id'=>$DeliveryDate->id)); ?>";
			var selectedDate=<?php echo $DeliveryDate ? "'$DeliveryDate->date'" : 'null' ?>;
			var availableDates=<?php echo json_encode(SnapUtil::makeArray($AllDeliveryDates)) ?>;
		</script>
		<div class="dropDown">
			<h4 data-dropdown="calendarDropdown">Delivery Date: <strong><?php echo $DeliveryDate ? Yii::app()->snapFormat->date($DeliveryDate->date) : 'None Selected'; ?></strong></h4>
			<div class="dropDownPanel" id="calendarDropdown">

				<div class="delivery-date-picker"></div>
				<div class="item-list">
					<h5>Your upcoming orders</h5>
					<?php foreach($DeliveryDates as $DD): 
					$total = $Customer->totalByDeliveryDate($DD->id);
					if($total == 0) continue;
					?>
					<div class="item">
						<p><?php echo CHtml::link(Yii::app()->snapFormat->date($DD->date), array('extras/order','date'=>$DD->id)) ?> 
						<span class="right"><?php echo Yii::app()->snapFormat->currency($total) ?></span></p>
					</div>
					<?php endforeach; ?>
					<p><strong><?php echo CHtml::link('View All',array('customer/orders'),array('class'=>'button small right')) ?></strong></p>
				</div>
			</div>
		</div>
	</div>
	
	<div class="large-4 columns end">
		<div class="dropDown">
			<h4 data-dropdown="repeatOrderDropdown">Repeat this order</h4>
			<div class="dropDownPanel" id="repeatOrderDropdown">
				<p>This will reset your orders for the selected amount of time to 
						the setting chosen here. Press the "Clear all orders" button to start over. </p>
				<div class="row">
					<div class="large-4 columns">
						<?php echo CHtml::label('Order in advance for', 'months_advance');  ?>
						<?php echo CHtml::dropDownList('months_advance',1,array(1=>'1 Month',3=>'3 Months',6=>'6 Months'));  ?>
					</div>
					<div class="large-4 columns">
						<?php echo CHtml::label('Starting from', 'starting_from');  ?>
						<?php echo CHtml::dropDownList('starting_from',1,$DeliveryDate->getFutureDeliveryDates());  ?>

					</div>
					<div class="large-4 columns">
						<?php echo CHtml::label('Every', 'every');  ?>
						<?php echo CHtml::dropDownList('every',1,array('week'=>'week','fortnight'=>'fortnight'));  ?>
					</div>
				</div>
				<div class="row">
					<div class="large-12 columns">
						<?php echo CHtml::submitButton('Set recurring order', array('name'=>'btn_recurring','class'=>'button small tiny right')); ?>
						<?php echo CHtml::submitButton('Clear all orders', array('name'=>'btn_clear_orders','class'=>'button small tiny right')); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php $this->endWidget(); ?>
	
</div>
<?php endif; ?>

<h1>Orders</h1>

<div class="row">
	<div class="large-2 columns leftCol">
		<h2>Categories</h2>
		<ul class="categories">
			<li class="<?php echo ($curCat==Category::boxCategory?'selected':'') ?>"><?php echo CHtml::link('Boxes', array('customerDeliveryDateItem/order','date'=>$DeliveryDate->id,'cat'=>Category::boxCategory)) ?></li>
			<?php echo Category::model()->getCategoryTree(Category::supplierProductRootID, array('customerDeliveryDateItem/order','date'=>$DeliveryDate->id), $curCat); ?>
			<li class="<?php echo ($curCat==Category::uncategorisedCategory?'selected':'') ?>"><?php echo CHtml::link('Uncategorised', array('customerDeliveryDateItem/order','date'=>$DeliveryDate->id,'cat'=>Category::uncategorisedCategory)) ?></li>
		</ul>
	</div>
	<div class="large-7 columns products">
		
		<?php if($curCat == Category::boxCategory): ?>
		<h2>Boxes</h2>
		<p>You may also select items you do not wish to have in your box <?php echo CHtml::link('here',array('user/dontWant','id'=>Yii::app()->user->id)) ?>.</p>
		<div class="items row list-view">
			<?php foreach($DeliveryDate->MergedBoxes as $Box): ?>

			<?php if($Box->BoxSize->box_size_name): ?>
			<div class="large-12 columns view">
				<?php $form=$this->beginWidget('CActiveForm', array(
					'id'=>'box-form-'.$Box->box_id,
					'enableAjaxValidation'=>false,
				)); ?>
				<div class="image">
				<?php if(!empty($Box->BoxSize->image)): ?>
					<?php echo CHtml::image($this->createUrl('boxSize/image',array('id'=>$Box->BoxSize->id,'size'=>'tiny'))); ?>
				<?php else: ?>
					<?php echo CHtml::image($this->createUrl('boxSize/image',array('size'=>'tiny'))); ?>
				<?php endif; ?>
				</div>
				<div class="inner">
					<div class="row">
						<div class="large-9 columns">
							<h3><?php echo $Box->BoxSize->box_size_name; ?> Box</h3>
							<span class="price">Price: <?php echo Yii::app()->snapFormat->currency($Box->box_price); ?></span>
							<span class="description"><?php echo $Box->BoxSize->description; ?></span>
						</div>

						<div class="large-3 columns qty">
							<?php echo CHtml::dropDownList('boxes['.$Box->box_id.']',1,CustomerBox::$quantityOptions); ?>
								<?php echo CHtml::submitButton('Add',array('class'=>'button tiny')); ?>
						</div>
					</div>
				</div>
				<?php $this->endWidget(); ?>
			</div>
			<?php endif; ?>

			<?php endforeach; ?>
		</div>
		
		<?php else: ?>
		
		<?php if($Category): ?>
			<h2><?php echo $Category->name ?></h2>
			<?php if(!empty($Category->description)): ?>
			<p><?php echo $Category->description ?></p>
			<?php endif; ?>
		<?php elseif($curCat == Category::uncategorisedCategory): ?>
			<h2>Uncategorised</h2>
		<?php endif; ?>

		<?php $this->widget('zii.widgets.CListView', array(
			'dataProvider'=>$availableInventory,
			'summaryText'=>'',
			'itemsCssClass'=>'items row',
			'emptyText'=>'No items found',
			'itemView'=>'../inventory/_view',
			'viewData'=>array('updatedExtras'=>$updatedExtras,'pastDeadline'=>$pastDeadline),
		)); ?>
				
		<?php endif; ?>
	</div>
	
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'extras-form',
		'enableAjaxValidation'=>false,
	)); ?>
	<div class="large-3 columns products order">
		<?php if($Customer): ?>
		<h2>Your order (<?php echo Yii::app()->snapFormat->currency($Customer->totalByDeliveryDate($DeliveryDate->id)) ?>)</h2>
		
		<div class="items row list-view">
		<?php foreach($DeliveryDate->MergedBoxes as $Box):
			$CustomerBox=CustomerBox::findCustomerBox($DeliveryDate->id, $Box->size_id, Yii::app()->user->customer_id);
			if(!$CustomerBox) 
				continue;
			$quantity=$CustomerBox ? $CustomerBox->quantity : 0; 
			?>
		
			<div class="view large-12 columns">
				<div class="row">
					<div class="large-4 columns">
						<div class="image">
						<?php if(!empty($Box->BoxSize->image)): ?>
							<?php echo CHtml::image($this->createUrl('boxSize/image',array('id'=>$Box->BoxSize->id))); ?>
						<?php else: ?>
							<?php echo CHtml::image($this->createUrl('boxSize/image')); ?>
						<?php endif; ?>
						</div>
					</div>
					<div class="large-8 columns">
						<h3><?php echo CHtml::encode($Box->BoxSize->box_size_name); ?> Box <br /><span class="each"><?php echo Yii::app()->snapFormat->currency($Box->box_price) ?> ea.<span></h3>
						<?php if(!$pastDeadline): ?>
							<div class="row">
								<div class="large-6 columns">
									<?php echo CHtml::dropDownList('boxes['.$Box->box_id.']', $quantity, CustomerBox::$quantityOptions); ?>
								</div>
								<div class="large-6 columns">
									<?php echo CHtml::submitButton('Update',array('class'=>'button tiny')); ?>
								</div>
							</div>
						<?php else: ?>
							<span class="quantity"><strong>Qty:</strong> <?php echo $quantity; ?></span>
						<?php endif; ?>
						<span class="price"><strong>Price:</strong> <?php echo $CustomerBox->total_price; ?> inc. Delivery </span>
					</div>
				</div>
			</div>
		
		<?php endforeach; ?>
		</div>
		
		<?php $this->widget('zii.widgets.CListView', array(
			'dataProvider'=>$orderedExtras,
			'summaryText'=>'',
			'itemsCssClass'=>'items row',
			'emptyText'=>'',
			'itemView'=>'../customerDeliveryDateItem/_view',
			'viewData'=>array('form'=>$form,'updatedOrders'=>$updatedOrders,'pastDeadline'=>$pastDeadline),
		)); ?>
		<?php else: ?>
		<h2>Your order</h2>
		<?php endif; ?>
	</div>
	<?php $this->endWidget(); ?>
</div>

