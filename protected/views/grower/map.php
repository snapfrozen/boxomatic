<?php
$cs = Yii::app()->clientScript;
$cs->registerScriptFile('http://maps.googleapis.com/maps/api/js?key=' . Yii::app()->params['googleMapKey'] . '&sensor=false');
$cs->registerScriptFile( Yii::app()->request->baseUrl . '/js/mustache.js' );
?>
<script type="text/javascript">
	var growers=<?php echo json_encode($Growers) ?>;
</script>

<h1>Grower Map</h1>
<div class="search-form">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div>
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
<?php echo CHtml::hiddenField('growerSearchUrl', $this->createUrl('grower/search')); ?>