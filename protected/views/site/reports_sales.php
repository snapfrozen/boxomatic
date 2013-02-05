<h1>Reports</h1>

<div class="form">
	<div class="left">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'login-form',
			'enableClientValidation'=>false,
			'clientOptions'=>array(
				'validateOnSubmit'=>false,
			),
		)); ?>
		<div class="row">
			<?php echo CHtml::label('Date From','date_from') ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'name'=>'date_from',
				'options'=>array(
					'dateFormat'=>'yy-mm-dd'
				)
			)); ?>
		</div>
		<div class="row">
			<?php echo CHtml::label('Date To','date_to') ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'name'=>'date_to',
				'options'=>array(
					'dateFormat'=>'yy-mm-dd'
				)
			)); ?>
		</div>
		<?php echo CHtml::submitButton('Box Sales',array('name'=>'boxSales')); ?>
		<?php $this->endWidget(); ?>
	</div>
	<div class="left">
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