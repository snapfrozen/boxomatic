<?php
	$this->menu=array(
		array('label'=>'List CustomerPayment', 'url'=>array('index')),
		array('label'=>'Create CustomerPayment', 'url'=>array('create')),
		array('label'=>'Update CustomerPayment', 'url'=>array('update', 'id'=>$model->payment_id)),
		array('label'=>'Login as Customer', 'url'=>array('user/LoginAs', 'id'=>$model->Customer->User->id)),
		array('label'=>'Delete CustomerPayment', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->payment_id),'confirm'=>'Are you sure you want to delete this item?')),
		array('label'=>'Manage CustomerPayment', 'url'=>array('admin')),
	);
?>

<div class="row">

	<div class="large-12 columns">
		<h1>Customer Payment</h1>
	</div>

	<div class="large-8 columns">
		<?php $this->widget('zii.widgets.CDetailView', array(
			'cssFile' => '', 
			'data'=>$model,
			'attributes'=>array(
				'payment_id',
				'Customer.User.full_name',
				'payment_type',
				'payment_value',
				'payment_date',
				'payment_note',
				array(
					'name'=>'Staff.full_name',
					'label'=>'Entered By'
				)
			),
		)); ?>
		
		<p>Payment made <strong><?php echo $model->payment_date; ?></strong></p>
	</div>

	<div class="large-4 columns">
		<?php echo CHtml::link('Login as Customer',array('user/loginAs', 'id'=>$model->Customer->User->id), array('class' => 'button')) ?>
	</div>

</div>
