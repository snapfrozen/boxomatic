var $recurringForm = $('form#reccuring-customer-order-form');
var $recurringInputs = $('form#reccuring-customer-order-form input[type="text"]');
var deliveryCost = parseFloat($('input#Location_location_delivery_value').val());

$recurringInputs.change(function(){
	
	var boxTotal=0;
	var deliveryTotal=0;
	
	$recurringInputs.each(function(){
		var quantity = parseInt($(this).val());
		var boxValue = parseFloat($(this).siblings('input:eq(0)').val()); 
		boxTotal += (quantity * boxValue);
		
		console.log(boxValue, quantity, boxTotal);
		
		deliveryTotal += deliveryCost * quantity;
	});
	
	$recurringForm.find('td.recBoxes').html('$'+boxTotal);
	$recurringForm.find('td.recDelivery').html('$'+deliveryTotal);
	$recurringForm.find('td.recTotal').html('$' + (boxTotal + deliveryTotal));
	
});

$('input.number').spinner();