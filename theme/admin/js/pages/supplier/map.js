var map;

function initialize() {
  var mapOptions = {
    center: new google.maps.LatLng(-25.274398, 133.775136),
    zoom: 4,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };

  var map = new google.maps.Map(document.getElementById("map-canvas"),
      mapOptions);

  $.each(suppliers, function(){
    	if(this.longitude !== defaultCoordinate && this.lattitude !== defaultCoordinate){
    		 var supplierLatlng = new google.maps.LatLng(this.lattitude, this.longitude);
    		 var marker = new google.maps.Marker({
    		       position: supplierLatlng,
    		       map : map,
    		       title : this.name,
               icon : SnapCMS.baseUrl+'/../themes/boxomatic/images/icons/map/carrot.png'
    		   });

    		var html = '<div class="info-window-content"><h3>' + this.name + '</h3>';
    		html += '<ul class="no-bullet">'
    		html += '<li>' + this.address + '</li>';
    		html += '<li>' + this.produce + '</li>';
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