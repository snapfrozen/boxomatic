<?php
$this->breadcrumbs=array(
	'Box-O-Matic'=>array('/snapcms/boxomatic/index'),
	'Reports'=>array('reports/index'),
	'Box Sales',
);
$this->menu=array(
//	array('icon' => 'glyphicon glyphicon-plus-sign', 'label'=>'Create Box Size', 'url'=>array('boxSize/create')),
);
$this->page_heading = 'Box Sales';
?>

<?php $form=$this->beginWidget('application.widgets.SnapActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>false,
	'clientOptions'=>array(
		'validateOnSubmit'=>false,
	),
	'layout' => BsHtml::FORM_LAYOUT_HORIZONTAL,
	'htmlOptions' => array('class'=>'row'),
)); ?>
	<div class="col-lg-9 clearfix">
		<div class="form-group">
			<?php echo BsHtml::label('Date From','date_from',array('class'=>BsHtml::$formLayoutHorizontalLabelClass)) ?>
			<div class="col-lg-10">
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'name'=>'date_from',
				'options'=>array(
					'dateFormat'=>'yy-mm-dd'
				),
				'htmlOptions'=>array('class'=>'form-control')
			)); ?>
			</div>
		</div>
		<div class="form-group">
			<?php echo BsHtml::label('Date To','date_to',array('class'=>BsHtml::$formLayoutHorizontalLabelClass)) ?>
			<div class="col-lg-10">
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'name'=>'date_to',
				'options'=>array(
					'dateFormat'=>'yy-mm-dd'
				),
				'htmlOptions'=>array('class'=>'form-control')
			)); ?>
			</div>
		</div>
		<button class="btn btn-primary pull-right" name="yt0" type="submit">
			<span class="glyphicon glyphicon-search"></span> Search
		</button>
	</div>
<?php $this->endWidget(); ?>
</div>

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