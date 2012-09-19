$('span.btnAdvanced').show();
$('input.number').spinner();
$('select.deliverySelect').selectmenu();
$('select.deliverySelect').removeClass('ui-widget')

var $orderFormHeadings = $('input.boxSizeLabel');
var $recurringForm = $('form#reccuring-customer-order-form');
var $recurringInputs = $('form#reccuring-customer-order-form input[type="text"]');
var deliveryCost = parseFloat($('input#Location_location_delivery_value').val());

$('select.deliverySelect').change(function(){
	var $elem = $(this);
	var $tr = $elem.parents('tr:eq(0)');
	$tr.next().find('.advanced input.number').trigger('change');
});

$recurringInputs.change(function(){
	var boxTotal=0;
	var deliveryTotal=0;
	
	$recurringInputs.each(function(){
		var quantity = parseInt($(this).val());
		var boxValue = parseFloat($(this).siblings('input:eq(0)').val()); 
		boxTotal += (quantity * boxValue);
		deliveryTotal += deliveryCost * quantity;
	});
	
	$recurringForm.find('td.recBoxes').html('$' + boxTotal.toFixed(2));
	$recurringForm.find('td.recDelivery').html('$' + deliveryTotal.toFixed(2));
	$recurringForm.find('td.recTotal').html('$' + (boxTotal + deliveryTotal).toFixed(2));
});

$('div.advanced input.number').change(function(){
	var $elem = $(this);
	var $tr = $elem.parents('tr:eq(0)');
	var $inputs = $tr.find('div.advanced input.number');
	var boxTotal=0;
	var deliveryTotal=0;
	var deliveryCost=locCosts[$tr.prev().find('select.deliverySelect').val()];
	
	$tr.addClass('dirty');
	
	$inputs.each(function(){
		var quantity = parseInt($(this).val());
		var boxValue = parseFloat($(this).siblings('input:eq(0)').val()); 
		boxTotal += (quantity * boxValue);
		deliveryTotal += deliveryCost * quantity;
	});
	
	$tr.find('td.boxes').html('$' + boxTotal.toFixed(2));
	$tr.find('td.delivery').html('$' + deliveryTotal.toFixed(2));
	$tr.find('td.total').html('$' + (boxTotal + deliveryTotal).toFixed(2));
});

$('span.btnAdvanced').click(function(){
	var $elem = $(this);
	
	if($elem.hasClass('selected')) {
		
		if($elem.parents('#reccuring-customer-order-form').length!=0 || window.confirm('This will reset your order for the week, are you sure?')) {
			
			$elem.removeClass('selected');
			$elem
				.parents('td:eq(0)')
				.next()
				.find('div.advanced')
				.removeClass('show')
				.next()
				.addClass('show');
			
				$elem
					.parents('td:eq(0)')
					.next()
					.find('div.advanced input.number').val(0)
					.trigger('change');

				$elem
					.parents('td:eq(0)')
					.next()
					.find('div.slider').slider('value',0);
		}
			
	} else {
		$elem.addClass('selected');
		$elem
			.parents('td:eq(0)')
			.next()
			.find('div.advanced')
			.addClass('show')
			.next()
			.removeClass('show');
	}

});

$('table div.slider').each(function(key,item){
	
	var $slider = $(item)
	var $fields = $slider.parents('td:eq(0)').find('.advanced input.number');
	var $sliderLabels = $slider.next();
	var sliderVal = 0;
	var disabled = false;
	
	if($slider.parents('tr:eq(0)').hasClass('pastDeadline')) {
		disabled = true;
	}
	
	var totalQuantity = 0;
	var percent = 0;
	var percentIncrement = 100/$fields.length;
	
	var $newSpan = $('<span>Nil</span>');
	$newSpan.css('left', percent + '%');
	percent += percentIncrement;
	$sliderLabels.append($newSpan);
	
	$fields.each(function(key){
		var quantity = parseInt($(this).val());
		totalQuantity += quantity;
		
		var $newSpan = $('<span>' + $orderFormHeadings.eq(key).val() +  '</span>');
		$newSpan.css('left', percent + '%');
		percent += percentIncrement;
		$sliderLabels.append($newSpan);
	});

	if(totalQuantity <= 1) //Hide advanced form if quantity less than one
	{
		$slider
			.parents('div.simple')
			.addClass('show')
			.prev()
			.removeClass('show')
			.prev();
		
		$slider
			.parents('tr:eq(0)')
			.find('.btnAdvanced')
			.removeClass('selected');
	}
	
	if(totalQuantity == 1)
	{
		var $field = $fields.filter('[value="1"]');
		sliderVal = $fields.index($field) + 1;
	}
	
	$slider.slider({
		min:0,
		max:$('input#box_size_count').val(),
		slide: function( event, ui ) {
			var index = ui.value-1;
			$fields.val(0);
			if(index >= 0) {
				$fields.eq(index).val(1);
			}
			$fields.eq(0).trigger('change');
		},
		value:sliderVal,
		disabled:disabled
	});
});
