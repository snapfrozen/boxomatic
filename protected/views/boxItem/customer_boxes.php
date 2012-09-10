<?php
	$cs=Yii::app()->clientScript;
	$cs->registerCssFile(Yii::app()->request->baseUrl . '/css/redmond/jquery-ui.css');
	
	$cs->registerCoreScript('jquery.ui');
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/ui.touch-punch.min.js', CClientScript::POS_END);
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/ui.datepicker.min.js', CClientScript::POS_END);
?>
<?php
    foreach(Yii::app()->user->getFlashes() as $key => $message) {
        echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
    }
?>
<h1>Customer Boxes</h1>

<div class="calendar">
	<h2><span>Delivery Date</span> <span class="loading"></span></h2>
	<div class="row">
		<script type="text/javascript">
			var curUrl="<?php echo $this->createUrl('boxItem/customerBoxes'); ?>";
			var selectedDate=<?php echo $SelectedWeek ? "'$SelectedWeek->week_delivery_date'" : 'null' ?>;
			var availableWeeks=<?php echo json_encode(SnapUtil::makeArray($Weeks)) ?>;
		</script>
		<div class="week-picker"></div>
		<noscript>
		<?php foreach($Weeks as $Week): ?>
			<?php echo CHtml::link($Week->week_delivery_date, array('boxItem/customerBoxes','week'=>$Week->week_id)) ?>, 
		<?php endforeach; ?>
		</noscript>
	</div>
</div>
<div id="customerList" class="row">
	<p><?php
		echo CHtml::link('Process Customers', array('boxItem/processCustomers','week'=>$SelectedWeek ? $SelectedWeek->week_id : -1), array('confirm'=>'Are you sure?'));
		echo ' ' . CHtml::link('Pack Boxes', array('boxItem/create','week'=>$SelectedWeek ? $SelectedWeek->week_id : -1));
	?> &nbsp;</p>
	
	<?php
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'customer-list-grid',
		'dataProvider'=> $SelectedWeek ? $CustomerBoxes->boxSearch($SelectedWeek->week_id) : $CustomerBoxes->boxSearch(-1),
		'filter'=>$CustomerBoxes,
		'columns'=>array(
			'customer_id',
			array(
				'name'=>'customer_first_name',
				'value'=>'$data->Customer->User->first_name'
			),
			array(
				'name'=>'customer_last_name',
				'value'=>'$data->Customer->User->last_name'
			),
			array(
				'name'=>'customer_box_price',
				'value'=>'Yii::app()->snapFormat->currency($data->Box->box_price + $data->delivery_cost)',
				'filter'=>CHtml::listData(BoxSize::model()->findAll(),'box_size_price','box_size_name')
			),
			array(
				'name'=>'Customer.balance',
				'value'=>'Yii::app()->snapFormat->currency($data->Customer->balance)',
			),
			array(
				'name'=>'status',
				'value'=>'$data->status_text',
				'filter'=>CustomerBox::model()->statusOptions,
			),
			array(
			'class'=>'CButtonColumn',
				'header'=>'Actions',
				'template'=>'{process}',
				'buttons'=>array(
					'process' => array
					(
						'url'=>'array("boxItem/processCustBox","custBox"=>$data->customer_box_id)',
						'visible'=>'$data->status==CustomerBox::STATUS_DECLINED'
					),
				),
			),
		),
	)); ?>
	
</div>