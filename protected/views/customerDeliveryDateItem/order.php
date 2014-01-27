<?php
/* @var $this CustomerDeliveryDateItemController */
/* @var $model CustomerDeliveryDateItem */

//$this->breadcrumbs=array(
//	'Customer Delivery Date Items'=>array('index'),
//	'Manage',
//);
?>
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
		<div class="items row">
			<?php foreach($DeliveryDate->MergedBoxes as $Box): ?>

			<?php if($Box->BoxSize->box_size_name): ?>
				<div class="large-4 columns end">
					<?php $form=$this->beginWidget('CActiveForm', array(
						'id'=>'box-form-'.$Box->box_id,
						'enableAjaxValidation'=>false,
					)); ?>
						<h3><?php echo $Box->BoxSize->box_size_name; ?> Box</h3>

						<div class="image">
						<?php if(!empty($Box->image)): ?>
							<?php echo CHtml::image($this->createUrl('box/image',array('id'=>$Box->id))); ?>
						<?php else: ?>
							<p>No image</p>
						<?php endif; ?>
						</div>

						<span class="price">Price: <?php echo Yii::app()->snapFormat->currency($Box->box_price); ?></span>
						<span class="description"><?php //echo $Box->description; ?></span>
						<?php echo CHtml::dropDownList('boxes['.$Box->box_id.']',1,CustomerBox::$quantityOptions); ?>
						<?php echo CHtml::submitButton('Add',array('class'=>'button small')); ?>
					<?php $this->endWidget(); ?>
				</div>
			<?php endif; ?>

			<?php endforeach; ?>
		</div>
		<?php endif; ?>
		
		<?php if($Category): ?>
			<h2><?php echo $Category->name ?></h2>
			<p><?php echo $Category->description ?></p>
		<?php elseif($curCat == Category::uncategorisedCategory): ?>
			<h2>Uncategorised</h2>
		<?php endif; ?>
		
		<?php $this->widget('zii.widgets.CListView', array(
			'dataProvider'=>$availableInventory,
			'summaryText'=>'',
			'itemsCssClass'=>'items row',
			'emptyText'=>'No items found',
			'itemView'=>'../inventory/_view',
			'viewData'=>array('updatedExtras'=>$updatedExtras),
		)); ?>
	</div>
	
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'extras-form',
		'enableAjaxValidation'=>false,
	)); ?>
	<div class="large-3 columns products order">
		<h2>Your order</h2>
		
		<div class="items row">
		<?php foreach($DeliveryDate->MergedBoxes as $Box):
			$CustomerBox=CustomerBox::findCustomerBox($DeliveryDate->id, $Box->size_id, Yii::app()->user->customer_id);
			if(!$CustomerBox) continue;
			$quantity=$CustomerBox ? $CustomerBox->quantity : 0; ?>
		
			<div class="view large-12 columns end">
				<div class="row">
					<div class="large-4 columns">
						<div class="image">
						<?php if(!empty($Box->image)): ?>
							<?php echo CHtml::image($this->createUrl('box/image',array('id'=>$Box->box_id))); ?>
						<?php else: ?>
							<p>No image</p>
						<?php endif; ?>
						</div>
					</div>
					<div class="large-8 columns">
						<h3><?php echo CHtml::encode($Box->BoxSize->box_size_name); ?> Box</h3>
						<span class="price"><?php echo $CustomerBox->total_box_price; ?> <span class="each">(<?php echo Yii::app()->snapFormat->currency($Box->box_price) ?> ea.)<span></span>
						<?php echo CHtml::dropDownList('boxes['.$Box->box_id.']', $quantity, CustomerBox::$quantityOptions); ?>
						<?php //echo CHtml::link('Remove',array('removeBoxes','id'=>$Box->box_id),array('class'=>'button small')) ?>
						<?php echo CHtml::submitButton('Update',array('class'=>'button small')); ?>
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
			'viewData'=>array('form'=>$form,'updatedOrders'=>$updatedOrders),
		)); ?>
	</div>
	<?php $this->endWidget(); ?>
</div>

