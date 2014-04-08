<?php
/* @var $this UserLocationController */
/* @var $model UserLocation */
?>

<h1>Orders</h1>
<?php if(Yii::app()->request->getParam('fromToday',1)==0) :?>
	<?php echo CHtml::link('View From Today',array('user/orders','id'=>$User->id,'fromToday'=>1),array('class'=>'button small')) ?>
<?php else: ?>
	<?php echo CHtml::link('View All',array('user/orders','id'=>$User->id,'fromToday'=>0),array('class'=>'button small')) ?>
<?php endif; ?>
<p>Your current balance is <?php echo SnapFormat::currency($User->balance) ?></p>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$ordersDP,
	'itemView'=>'_order_view',
)); ?>

