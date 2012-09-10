var bwStyles = [
	/*
	{
		featureType: "landscape",
		elementType: "all",
		stylers: [ { hue: '#ceeacd' }, { saturation: 19 }, { lightness: -3 }, { visibility: 'on' } ]
	},
	{
		featureType: "poi",
		elementType: "all",
		stylers:  [ { hue: '#abcbaa' }, { saturation: -11 }, { lightness: -18 }, { visibility: 'on' } ]
	}*/
];
var bwMapType = new google.maps.StyledMapType(bwStyles, {name: "Black and White Map"});
var searchMap = null;
var searchMarkers=[]

jQuery(document).ready(function() {

	//Central Australia latitude and Longitude for default
	var latLng = new google.maps.LatLng(-32.10, 147.96);
	var myOptions = {
		center: latLng,
		zoom: 5,
		disableDefaultUI: true,
		zoomControl: true,
		mapTypeControlOptions: {
		mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'bw_map']
		}
	};
	
	searchMap = new google.maps.Map(document.getElementById("map"), myOptions);

	searchMap.mapTypes.set('bw_map', bwMapType);
	searchMap.setMapTypeId('bw_map');

	addMarkers();
	
	
	$('form#grower-search').find('input[name="r"]').remove();
	$('form#grower-search').submit(function(){
		var postUrl=$('input#growerSearchUrl').val();
		$.ajax({
			url:postUrl,
			type:'GET',
			data:$(this).serialize(),
			dataType:'json',
			success:function(data) {
				growers=data;
				addMarkers();
			}
		})
		return false;
	});
});

function addMarkers()
{
	var infowindow = new google.maps.InfoWindow();
	var template = $('div#infoWindowTemplate').html();
	
	$(searchMarkers).each(function(key, marker){
		marker.setMap(null);
	});
	searchMarkers = [];

	jQuery.each(growers, function(key, item) {
		
		var pos = new google.maps.LatLng(parseFloat(item.lattitude), parseFloat(item.longitude))
		var icon = new google.maps.MarkerImage("images/icons/map/carrot.png", null, null, new google.maps.Point(35,35));
		
		var marker = new google.maps.Marker({
			map: searchMap,
			position: pos,
			icon: icon
		});

		var content = Mustache.to_html(template, item);
		google.maps.event.addListener(marker, 'click', function() {
			infowindow.close()
			infowindow.setContent(content);
			infowindow.open(searchMap, marker);
		});

		searchMarkers.push(marker);
	});
}