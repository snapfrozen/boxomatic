(function($) {
	
var $loading=$('span.loading');
$loading.css({display:'inline-block'});
$loading.hide();

function loadSpinners()
{
	$('select.chosen').chosen();

	$('input.number, input.decimal, input.currency').stepper({
		arrow_step : 0.5, 
		limit : [0,]
	});

	// $('div.sticky').stickyScroll({ container: '#current-boxes' });
}
loadSpinners();

function reloadBoxes(url,data)
{
	var ajaxUpdate = ['current-boxes','supplier-item-grid'];
	$loading.show();
	$.ajax({
		type: 'GET',
		url: url,
		data: data,
		success: function(data,status) {
			$.each(ajaxUpdate, function(i,v) {
				var id='#'+v;
				$(id).replaceWith($(id,'<div>'+data+'</div>'));
			});
			loadSpinners();
			$loading.hide();
		}
	});
}

$('div#inventory, form#box-item-form').on('click', 'table td:not(.button-column) a', function(){
	var $a = $(this);
	reloadBoxes($a.attr('href'),{});
	return false;
});

$('form#box-item-form').on('change', 'input, select', function(){
	$(this).parent('td').addClass('dirty');
});

$('form#box-item-form').on('submit', function() {

	$loading.show();
	var ajaxUpdate = ['current-boxes','supplier-item-grid'];
	$.ajax({
		type: 'POST',
		url: $('input#curUrl').val(),
		data: $(this).find('input, select').serialize(), //only post data from the row that was changed
		success: function(data,status) {
			$.each(ajaxUpdate, function(i,v) {
				var id='#'+v;
				$(id).replaceWith($(id,'<div>'+data+'</div>'));
				$loading.hide();
			});
			loadSpinners();
		}
	});
	
	return false;
});

$('.delivery-date-picker').datepicker({
	showOtherMonths: true,
	selectOtherMonths: true,
	numberOfMonths: 1,
	onSelect: function(dateText, inst) {
		var date = $(this).datepicker('getDate');
		
		//availableDates is a global variable found in the boxItem/create page
		$.each(availableDates, function(key,curDate){
			var parts = curDate['date'].split('-');
			var dateObj = new Date(parts[0],parts[1]-1,parts[2]);
			if (dateObj.getTime() == date.getTime()) {
				//reloadBoxes(curUrl,{date: curDate['id']});
				window.location.href=curUrl+'?date='+curDate['id']
			}
		});
	},
	beforeShowDay: function(date) {

		var found = false;
		var dateNotes = null;
		var oSelDate = null;
		var cssClass = ''
		
		//selectedDate is a global variable found in the boxItem/create page
		if(selectedDate)
		{
			var selParts = selectedDate.split('-');
			oSelDate = new Date(selParts[0],selParts[1]-1,selParts[2]);
		}
		
		//availableDates is a global variable found in the boxItem/create page
		$.each(availableDates, function(key,curDate) {
			var parts = curDate['date'].split('-');
			
			if(curDate['notes'])
				dateNotes = curDate['notes'];
			else 
				dateNotes = null;
			
			var oDeliveryDate = new Date(parts[0],parts[1]-1,parts[2]);
			
			if(oSelDate && oSelDate.getTime() == date.getTime()) {
				cssClass = 'ui-state-active';
			}
			
			if (oDeliveryDate.getTime() == date.getTime()) {
				found=true;
				return false; //break out of each loop
			}
		});

		if(found)
			return [true,cssClass,dateNotes];
		else
			return [false,cssClass,dateNotes];

	}
});

var BOXCOL_WIDTH = 70;
var TABLE_WIDTH_BALANCE = 710;

var $scrollTable = $('div#current-boxes table');
var tableWidth = (BOXCOL_WIDTH * $scrollTable.find('.boxCol').length) + TABLE_WIDTH_BALANCE;
$scrollTable.width(tableWidth);

})(jQuery);