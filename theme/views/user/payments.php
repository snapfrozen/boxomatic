<div class="row">
	<h1>All Transactions</h1>
	<?php echo CHtml::link('Add credit',array('user/makePayment'), array('class' => 'button small')); ?>
	<?php $this->widget('zii.widgets.CListView', array(
		'dataProvider'=>$dataProvider,
		'itemView'=>'_payment_view',
	)); ?>
</div>
