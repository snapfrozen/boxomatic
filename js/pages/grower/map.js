var map;

function initialize() {
  var mapOptions = {
    center: new google.maps.LatLng(-25.274398, 133.775136),
    zoom: 4,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };

  var map = new google.maps.Map(document.getElementById("map-canvas"),
      mapOptions);

  $.each(growers, function(){
    	if(this.longitude !== defaultCoordinate && this.lattitude !== defaultCoordinate){
    		 var growerLatlng = new google.maps.LatLng(this.lattitude, this.longitude);
    		 var marker = new google.maps.Marker({
    		       position: growerLatlng,
    		       map : map,
    		       title : this.grower_name,
               icon : 'images/icons/map/carrot.png'
    		   });

    		var html = '<div class="info-window-content"><h3>' + this.grower_name + '</h3>';
    		html += '<ul class="no-bullet">'
    		html += '<li>' + this.grower_address + '</li>';
    		html += '<li>' + this.grower_produce + '</li>';
    		html += '</ul>';
    		html += '</div>';

  		var infowindow = new google.maps.InfoWindow({
  			content: html
  		});

  		google.maps.event.addListener(marker, 'click', function() {
  			infowindow.open(map,marker);
  		});
    }
  });
}

$(function(){
	google.maps.event.addDomListener(window, 'load', initialize);
});