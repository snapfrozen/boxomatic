<?php
$this->breadcrumbs=array(
	'Box-O-Matic'=>array('/snapcms/boxomatic/index'),
	'Reports'=>array('reports/index'),
	'Credit',
);
$this->menu=array(
//	array('icon' => 'glyphicon glyphicon-plus-sign', 'label'=>'Create Box Size', 'url'=>array('boxSize/create')),
);
$this->page_heading = 'Credit Report';
?>
<div class="form">
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
<p>&nbsp;</p>
<div class="row">
	<div class="col-lg-9 clearfix">
		
		<?php if($series): 		
		//print_r(CJSON::encode($series[0]['data']));
		$this->Widget('boxomatic.extensions.highcharts.HighchartsWidget', array(
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
		<p>&nbsp;</p>
		<?php
		$this->beginWidget('bootstrap.widgets.BsPanel', array(
			'title'=>'Current credit by user',
			'titleTag'=>'h2',
		));
		?>
		<table class="table">
			<thead>
				<tr>
					<th>id</th>
					<th>Name</th>
					<th>Balance</th>
				</tr>
			</thead>
			<tbody>
				<?php $Custs=BoxomaticUser::model()->findAll();
				$total=0;
				foreach($Custs as $Cust):
					$bal=(float)CHtml::value($Cust, 'balance');
					$total+=$bal;
				?>
				<?php if($bal!=0): ?>
				<tr>
					<td><?php echo CHtml::value($Cust, 'id' ); ?></td>
					<td><?php echo CHtml::value($Cust, 'full_name') ?></td>
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
		<?php $this->endWidget(); ?>
		
	</div>
</div>