<h1>All Transactions</h1>
<p><?php echo CHtml::link('Add credit',array('customerPayment/create')) ?></p>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
