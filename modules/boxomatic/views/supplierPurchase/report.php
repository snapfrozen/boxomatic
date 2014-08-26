<?php
$this->breadcrumbs=array(
	'Box-O-Matic'=>array('/snapcms/boxomatic/index'),
	'Suppliers'=>array('supplier/admin'),
	'Orders'=>array('supplierPurchase/admin'),
	'Reports'
);
$this->menu=array(
//	array('icon' => 'glyphicon glyphicon-plus-sign', 'label'=>'Create Purchase', 'url'=>array('create')),
//	array('icon' => 'glyphicon glyphicon-stats', 'label'=>'Reports', 'url'=>array('report')),
);
$this->page_heading = 'Reports';
?>
<?php $form=$this->beginWidget('application.widgets.SnapActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>false,
	'layout' => BsHtml::FORM_LAYOUT_HORIZONTAL,
	'clientOptions'=>array(
		'validateOnSubmit'=>false,
	),
)); ?>
	<div class="form-group">
		<?php echo BsHtml::label('Date From','date_from',array('class'=>'col-lg-2')) ?>
		<div class="col-lg-10 ">
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
			'name'=>'date_from',
			'value'=>Yii::app()->request->getPost('date_from',''),
			'options'=>array(
				'dateFormat'=>'yy-mm-dd'
			),
			'htmlOptions'=>array('class'=>'form-control')
		)); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo CHtml::label('Date To','date_to',array('class'=>'col-lg-2')) ?>
		<div class="col-lg-10 ">
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
			'name'=>'date_to',
			'value'=>Yii::app()->request->getPost('date_to',''),
			'options'=>array(
				'dateFormat'=>'yy-mm-dd'
			),
			'htmlOptions'=>array('class'=>'form-control')
		)); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo CHtml::label('Supplier','Supplier',array('class'=>'col-lg-2')) ?>
		<div class="col-lg-10 ">
		<?php echo CHtml::dropDownList('Supplier',Yii::app()->request->getPost('Supplier'),Supplier::getDropdownListItems(),array('prompt'=>' - Select - ','class'=>'form-control')) ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo CHtml::label('Produce Type','SupplierProduct',array('class'=>'col-lg-2')) ?>
		<div class="col-lg-10 ">
		<?php echo CHtml::dropDownList('SupplierProduct',Yii::app()->request->getPost('SupplierProduct'),SupplierProduct::getDropdownListItems(),array('prompt'=>' - Select - ','class'=>'form-control')) ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo CHtml::label('Organic Status','organicStatus',array('class'=>'col-lg-2')) ?>
		<div class="col-lg-10 ">
		<?php echo CHtml::dropDownList('organicStatus',Yii::app()->request->getPost('organicStatus'),Supplier::getOSDropdownListItems(),array('prompt'=>' - Select - ','class'=>'form-control')) ?>
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-10 col-lg-offset-2">
			<?php echo BsHtml::submitButton('Box Sales',array('name'=>'boxSales', 'class' => 'button', 'color'=>BsHtml::BUTTON_COLOR_PRIMARY)); ?>
		</div>
	</div>
<?php $this->endWidget(); ?>

<?php if($series): ?>
<div class="row">
	<div class="col-md-12">
	<?php //print_r(CJSON::encode($series[0]['data']));
	$this->Widget('boxomatic.extensions.highcharts.HighchartsWidget', array(
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
	)); ?>
	</div>
</div>
<?php endif; ?>
