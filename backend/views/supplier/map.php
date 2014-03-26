<?php
$baseUrl = $this->createFrontendUrl('/').'/themes/boxomatic/admin';
$cs=Yii::app()->clientScript;
$cs->registerScriptFile('https://maps.googleapis.com/maps/api/js?key='.SnapUtil::config('boxomatic/googleMapKey').'&sensor=false', CClientScript::POS_END);
$cs->registerScriptFile($baseUrl . '/js/pages/supplier/map.js', CClientScript::POS_END);
$default_latlong = '0.000000000000';

$this->breadcrumbs=array(
	'Box-O-Matic'=>array('/snapcms/boxomatic/index'),
	'Suppliers'=>array('supplier/admin'),
	'Supplier Map',
);
$this->menu=array(
	//array('icon' => 'glyphicon glyphicon-plus-sign', 'label'=>'Add Product', 'url'=>array('create')),
);
$this->page_heading = 'Suppiler';
$this->page_heading_subtext = 'Map';
?>
<script>
	var defaultCoordinate = '<?php echo $default_latlong; ?>';
	var suppliers = <?php echo json_encode($Suppliers); ?>;
</script>

<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
<p>&nbsp;</p>
<div class="row">
	<div class="col-md-9">
		<?php
		$this->beginWidget('bootstrap.widgets.BsPanel', array(
			'title'=>'Suppliers',
			'titleTag'=>'h3',
		));
		?>
			<div id="map-canvas" style="height:720px;"></div>
		<?php $this->endWidget() ?>
	</div>
</div>

