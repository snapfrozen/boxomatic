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
			'title' => array('text' => 'Box Sales'),
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
</div>