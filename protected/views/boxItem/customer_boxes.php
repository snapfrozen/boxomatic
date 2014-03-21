<?php
	$cs=Yii::app()->clientScript;
	$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/ui-lightness/jquery-ui.css');
	
	$cs->registerCoreScript('jquery.ui');
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/ui.touch-punch.min.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/ui.datepicker.min.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/boxitem/customerboxes.js',CClientScript::POS_END);
	
Yii::app()->clientScript->registerScript('initPageSize',<<<EOD
	$('.change-pageSize').live('change', function() {
		$.fn.yiiGridView.update('customer-list-grid',{ data:{ pageSize: $(this).val() }})
	});
	$('.search-form form').submit(function(){
		$.fn.yiiGridView.update('customer-grid', {
			data: $(this).serialize()
		});
		return false;
	});
EOD
,CClientScript::POS_READY);
?>

<h1>Customer Boxes</h1>
<div class="row">
	<div class="large-4 columns">
		<div class="calendar">
			<h3><span>Delivery Date</span> <span class="loading"></span></h3>
			<div>
				<script type="text/javascript">
					var curUrl="<?php echo $this->createUrl('boxItem/customerBoxes'); ?>";
					var selectedDate=<?php echo $SelectedDeliveryDate ? "'$SelectedDeliveryDate->date'" : 'null' ?>;
					var availableDates=<?php echo json_encode(SnapUtil::makeArray($DeliveryDates)) ?>;
				</script>
				<div class="delivery-date-picker"></div>
				<noscript>
				<?php foreach($DeliveryDates as $DeliveryDate): ?>
					<?php echo CHtml::link($DeliveryDate->date, array('boxItem/customerBoxes','date'=>$DeliveryDate->id)) ?>, 
				<?php endforeach; ?>
				</noscript>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div id="customerList" class="large-12 columns">
		<?php 
		if($SelectedDeliveryDate):
			if(strtotime($SelectedDeliveryDate->deadline) < time()):
				echo CHtml::link('Process Customers', array('boxItem/processCustomers','date'=>$SelectedDeliveryDate->id), array('id'=>'process', 'class' => 'button small'));
			endif;
			echo ' ' . CHtml::link('Pack Boxes', array('boxItem/create','date'=>$SelectedDeliveryDate->id), array('class' => 'button small'));
		endif; ?>

		<h2>Boxes</h2>
		<?php $dataProvider=$SelectedDeliveryDate ? $CustomerBoxes->boxSearch($SelectedDeliveryDate->id) : $CustomerBoxes->boxSearch(-1); ?>
		<?php $pageSize=Yii::app()->user->getState('pageSize',10); ?>
		<?php
		$this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'customer-list-grid',
			'cssFile' => '',
			'dataProvider'=> $dataProvider,
			'filter'=>$CustomerBoxes,
			'summaryText'=>'Displaying {start}-{end} of {count} result(s). ' .
			CHtml::dropDownList(
				'pageSize',
				$pageSize,
				array(5=>5,10=>10,20=>20,50=>50,100=>100),
				array('class'=>'change-pageSize')) .
			' rows per page',
			'columns'=>array(
				array(
					'name'=>'customer_user_id',
					'value'=>'$data->Customer->User->id'
				),
				array(
					'name'=>'customer_full_name',
					'type'=>'raw',
					'value'=>'CHtml::link($data->Customer->User->full_name,array("user/view","id"=>$data->Customer->User->id))'
				),
				array(
					'name'=>'Customer.balance',
					'value'=>'Yii::app()->snapFormat->currency($data->Customer->balance)',
				),
				array(
					'name'=>'customer_box_price',
					'value'=>'Yii::app()->snapFormat->currency($data->Box->box_price + $data->delivery_cost)',
					'filter'=>CHtml::listData(BoxSize::model()->findAll(),'box_size_price','box_size_name')
				),
				array(
					'name'=>'status',
					'value'=>'$data->status_text',
					'filter'=>CustomerBox::model()->statusOptions,
				),
				array(
				'class'=>'application.components.snap.SnapButtonColumn',
					'header'=>'Actions',
					'template'=>'{login}{process}{cancel}{set_approved}{set_delivered}',
					'buttons'=>array(
						'login' => array
						(
							'label'=>'<i class="fi fi-key"></i>',
							'url'=> 'array("user/loginAs","id"=>$data->Customer->User->id)',
							'options'=>array('title'=>'Login As'),
						),
						'process'=>array
						(
							'url'=>'array("boxItem/processCustBox","custBox"=>$data->customer_box_id)',
							'visible'=>'$data->status==CustomerBox::STATUS_DECLINED',
						),
						'cancel'=>array
						(
							'url'=>'array("boxItem/refund","custBox"=>$data->customer_box_id)',
							'visible'=>'$data->status==CustomerBox::STATUS_APPROVED',
							'label'=>'<i class="fi fi-minus"></i>',
							'options'=>array('confirm'=>'Are you sure you want to refund this box?','title'=>'Cancel & Refund'),
						),
						'set_approved'=>array
						(
							'url'=>'array("boxItem/setApproved","custBox"=>$data->customer_box_id)',
							'visible'=>'$data->status==CustomerBox::STATUS_DELIVERED',
							'label'=>'<i class="fi fi-check"></i>',
							'options'=>array('confirm'=>'Are you sure you want to set this box to Approved?','title'=>'Set Approved'),
						),
						'set_delivered'=>array
						(
							'url'=>'array("boxItem/setDelivered","custBox"=>$data->customer_box_id)',
							'visible'=>'$data->status==CustomerBox::STATUS_APPROVED',
							'label'=>'<i class="fi fi-shopping-bag"></i>',
							'options'=>array('confirm'=>'Are you sure you want to set this box to Collected/Delivered?','title'=>'Set Delivered'),
						)
					),
				),
			),
		)); ?>
		
		<h2>Extras</h2>
<!--		
		<div class="search-form">
			<?php $form=$this->beginWidget('CActiveForm', array(
				'action'=>Yii::app()->createUrl($this->route),
				'method'=>'get',
			)); ?>
				<?php echo CHtml::activeLabel($CustomerBoxes,'search_extras');?>
				<?php echo CHtml::activeTextField($CustomerBoxes,'search_extras');?>
				<div class="buttons">
					<?php echo CHtml::submitButton('Search',array('class'=>'button')); ?>
				</div>
			<?php $this->endWidget(); ?>
		</div>
		-->
		<?php
		$this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'customer-extras-grid',
			'cssFile' => '',
			'dataProvider'=> $CDDsWithExtras,
			'filter'=>$CDD,
			'summaryText'=>'Displaying {start}-{end} of {count} result(s). ' .
			CHtml::dropDownList(
				'pageSize',
				$pageSize,
				array(5=>5,10=>10,20=>20,50=>50,100=>100),
				array('class'=>'change-pageSize')) .
			' rows per page',
			'columns'=>array(
				array(
					'name'=>'customer_user_id',
					'value'=>'$data->Customer->User->id'
				),
				array(
					'name'=>'search_full_name',
					'type'=>'raw',
					'value'=>'CHtml::link(CHtml::value($data,"Customer.User.full_name"),array("user/view","id"=>$data->Customer->User->id))',
				),
				array(
					'name'=>'Customer.balance',
					'value'=>'Yii::app()->snapFormat->currency($data->Customer->balance)',
				),
				array(
					'name'=>'extras_total',
					'value'=>'Yii::app()->snapFormat->currency($data->extras_total)',
					'filter'=>false,
				),
				array(
					'name'=>'extras_item_names',
					'value'=>'substr($data->extras_item_names,0,20)."..."'
				),
				array(
					'name'=>'status',
					'value'=>'$data->status_text',
					'filter'=>CustomerDeliveryDate::model()->statusOptions,
				),
				array(
				'class'=>'application.components.snap.SnapButtonColumn',
					'header'=>'Actions',
					'template'=>'{login}{process}{cancel}{set_approved}{set_delivered}',
					'buttons'=>array(
						'login' => array
						(
							'label'=>'<i class="fi fi-key"></i>',
							'url'=> 'array("user/loginAs","id"=>$data->Customer->User->id)',
							'options'=>array('title'=>'Login As'),
						),
						'process'=>array
						(
							'url'=>'array("customer/processCustExtras","cdd"=>$data->id)',
							'visible'=>'$data->status==CustomerDeliveryDate::STATUS_DECLINED',
						),
						'cancel'=>array
						(
							'url'=>'array("customer/refundExtras","cdd"=>$data->id)',
							'visible'=>'$data->status==CustomerDeliveryDate::STATUS_APPROVED',
							'label'=>'<i class="fi fi-minus"></i>',
							'options'=>array('confirm'=>'Are you sure you want to refund this order?','title'=>'Cancel & Refund'),
						),
						'set_approved'=>array
						(
							'url'=>'array("customer/setExtrasApproved","cdd"=>$data->id)',
							'visible'=>'$data->status==CustomerDeliveryDate::STATUS_DELIVERED',
							'label'=>'<i class="fi fi-check"></i>',
							'options'=>array('confirm'=>'Are you sure you want to set this box to Approved?','title'=>'Set Approved'),
						),
						'set_delivered'=>array
						(
							'url'=>'array("customer/setExtrasApproved","cdd"=>$data->id)',
							'visible'=>'$data->status==CustomerDeliveryDate::STATUS_APPROVED',
							'label'=>'<i class="fi fi-shopping-bag"></i>',
							'options'=>array('confirm'=>'Are you sure you want to set this box to Collected/Delivered?','title'=>'Set Delivered'),
						)
					),
				),
			),
		)); ?>
	</div>

</div>

