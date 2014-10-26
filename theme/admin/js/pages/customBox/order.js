$(function(){
	$('#customer-order-form').find('.numeric').change(function(event){

		var rowParent 		= $(this).parents('li');
		var boxes 			= rowParent.find('.boxes');
		var delivery 		= rowParent.find('.delivery');
		var tempDelivery 	= parseFloat(delivery.html().replace(/[$,]+/g, ""));
		var total 			= rowParent.find('.total');
		var tempTotal 		= 0;

		rowParent.find('.numeric').each(function(){
			var priceValue = $(this).parent().next().val()
			tempTotal+=parseInt($(this).val()) * parseInt(priceValue);
		});

		boxes.html('$' + parseFloat(tempTotal).toFixed(2));
		total.find('strong').html('$' + parseFloat(tempTotal + tempDelivery).toFixed(2));

	}).stepper({
		limit : [0,],
		onStep : function(val, up){
			$(this).trigger('change');
		}
	});

	$('#reccuring-customer-order-form').find('.numeric').change(function(){

		var rowParent = $(this).parents('table');
		var boxes = rowParent.find('.box');
		var total = rowParent.find('.total');
		var tempTotal = 0;

		rowParent.find('.numeric').each(function(){
			var priceValue = $(this).parent().next().val();
			tempTotal += parseInt($(this).val()) * parseInt(priceValue);
		});

		boxes.html('$' + parseFloat(tempTotal).toFixed(2));
		total.find('strong').html('$' + parseFloat(tempTotal).toFixed(2));

	}).stepper({
		limit : [0,], 
		onStep : function(val, up){
			$(this).trigger('change');
		}
	});

});