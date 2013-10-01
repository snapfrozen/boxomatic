<?php
$cs = Yii::app()->clientScript;
// $cs->registerScriptFile('http://maps.googleapis.com/maps/api/js?key=' . Yii::app()->params['googleMapKey'] . '&sensor=false');
/*$cs->registerScriptFile('http://maps.googleapis.com/maps/api/js?key=' . 'AIzaSyA6ApufSw9cDFsBdgPE0vyRkrQUS7w5UJs' . '&sensor=false');*/
// $cs->registerScriptFile( Yii::app()->request->baseUrl . '/js/mustache.js', CClientScript::POS_END);
$cs->registerScriptFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyCLstfI4LnCwNmwcipEJeDKS1hqy1TcV3A&sensor=false', CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/pages/grower/map.js', CClientScript::POS_END);
$default_latlong = '0.000000000000';
?>

<script>
	var defaultCoordinate = '<?php echo $default_latlong; ?>';
	var growers = <?php echo json_encode($Growers); ?>;
</script>

<div class="row">
	<div class="large-12 columns">
		<h1>Grower Map</h1>
	</div>

	<div class="large-12 columns">
		<div class="panel">
			<a href="index.php?r=grower/index" class='button small'>List Grower</a>
			<a href="index.php?r=grower/create" class='button small'>Create Grower</a>
			<a href="index.php?r=grower/admin" class='button small'>Manage Grower</a>
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


