<div class="row">
	<div class="large-12 columns">
		<h1>Reports</h1>
	</div>
	<div class="large-12 columns">
		<?php $form=$this->beginWidget('application.widgets.SnapActiveForm', array(
			'id'=>'login-form',
			'enableClientValidation'=>false,
			'clientOptions'=>array(
				'validateOnSubmit'=>false,
			),
		)); ?>
		<fieldset>
			<legend>Date Filter</legend>
			<div class="large-6 columns">
				<?php echo CHtml::label('Date From','date_from') ?>
				<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
					'name'=>'date_from',
					'options'=>array(
						'dateFormat'=>'yy-mm-dd'
					)
				)); ?>
			</div>
			<div class="large-6 columns">
				<?php echo CHtml::label('Date To','date_to') ?>
				<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
					'name'=>'date_to',
					'options'=>array(
						'dateFormat'=>'yy-mm-dd'
					)
				)); ?>
			</div>
		</fieldset>
		<?php echo CHtml::submitButton('Box Sales',array('name'=>'boxSales', 'class' => 'button')); ?>
		<?php $this->endWidget(); ?>
	</div>
	<div class="large-12 columns">
		<?php if($series): 
			
		//print_r(CJSON::encode($series[0]['data']));
			
		$this->Widget('ext.highcharts.HighchartsWidget', array(
			'options'=>array(
				'title' => array('text' => 'Credit in system'),
				'xAxis' => array(
					'type' => 'datetime',
		//					'dateTimeLabelFormats'=>array( // don't display the dummy year
		//						'month'=>'%e. %b',
		//						'year'=>'%b'
		//					),
					//'categories' => $xAxis,
				),
				'yAxis' => $yAxis,
				'series' => $series
			)
		));
		endif; ?>
	</div>
	<div class="large-12 columns">
		<h2>Current credit by user</h2>
	</div>
	<div class="large-12 columns">
		<table>
			<thead>
				<th>id</th>
				<th>Name</th>
				<th>Balance</th>
			</thead>
			<tbody>
				<?php $Custs=Customer::model()->findAll();
				$total=0;
				foreach($Custs as $Cust):
					$bal=(float)CHtml::value($Cust, 'balance');
					$total+=$bal;
				?>
				<?php if($bal!=0): ?>
				<tr>
					<td><?php echo CHtml::value($Cust, 'User.id' ); ?></td>
					<td><?php echo CHtml::value($Cust, 'User.first_name') . ' ' . CHtml::value($Cust, 'User.last_name') ?></td>
					<td><?php echo SnapFormat::currency($bal) ?></td>
				</tr>
				<?php endif;?>
				<?php endforeach;?>
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td><?php echo SnapFormat::currency($total); ?></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>