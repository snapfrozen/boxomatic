<div class="row">
	<h1>All Transactions</h1>
	<?php echo CHtml::link('Add credit',array('customerPayment/create'), array('class' => 'button small')); ?>
	<?php $this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'_view',
	)); ?>
</div>
