$('select#CustomerDeliveryDate_delivery_location_key').change(function(){
	window.location = curUrlWithId + '?location=' + $(this).val();
});