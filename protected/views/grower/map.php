<?php
$cs = Yii::app()->clientScript;
$cs->registerScriptFile('http://maps.googleapis.com/maps/api/js?key=' . Yii::app()->params['googleMapKey'] . '&sensor=false');
$cs->registerScriptFile( Yii::app()->request->baseUrl . '/js/mustache.js' );

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('grower-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<script type="text/javascript">
	var growers=<?php echo json_encode($Growers) ?>;
</script>

<h1>Grower Map</h1>

<div id="map"></div>
<div id="infoWindowTemplate" class="hidden">
	<div class="infoWindow">
		<h2><a href="<?php echo $this->createUrl('grower/view'); ?>&id={{grower_id}}">{{grower_name}}</a></h2>
		<h3>Items available</h3>
		<ul>
		{{#GrowerItems}}
			<li>{{item_name}}</li>
		{{/GrowerItems}}
		</ul>
	</div>
</div>