<?php
$cs = Yii::app()->clientScript;
// $cs->registerScriptFile('http://maps.googleapis.com/maps/api/js?key=' . Yii::app()->params['googleMapKey'] . '&sensor=false');
/*$cs->registerScriptFile('http://maps.googleapis.com/maps/api/js?key=' . 'AIzaSyA6ApufSw9cDFsBdgPE0vyRkrQUS7w5UJs' . '&sensor=false');*/
// $cs->registerScriptFile( Yii::app()->request->baseUrl . '/js/mustache.js', CClientScript::POS_END);
$cs->registerScriptFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyCLstfI4LnCwNmwcipEJeDKS1hqy1TcV3A&sensor=false', CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/pages/supplier/map.js', CClientScript::POS_END);
$default_latlong = '0.000000000000';
?>

<script>
	var defaultCoordinate = '<?php echo $default_latlong; ?>';
	var suppliers = <?php echo json_encode($Suppliers); ?>;
</script>

<div class="row">
	<div class="large-12 columns">
		<h1>Supplier Map</h1>
	</div>

	<div class="large-12 columns">
		<div class="panel">
			<?php echo CHtml::link('List Supplier',array('supplier/index'),array('class'=>'button small')) ?>
			<?php echo CHtml::link('Create Supplier',array('supplier/create'),array('class'=>'button small')) ?>
			<?php echo CHtml::link('Manage Supplier',array('supplier/admin'),array('class'=>'button small')) ?>
		</div>
	</div>

	<div class="large-12 columns">
		<div class="search-form">
			<?php $this->renderPartial('_search',array(
				'model'=>$model,
			)); ?>
		</div>
	</div>

	<div class="large-12 columns">
		<div id="map-canvas" style='height:720px;'></div>
	</div>
</div>


