var $loading=$('span.loading');
$loading.css({display:'inline-block'});
$loading.hide();

$('div.sticky').stickyScroll({ container: '#current-boxes' });

function loadSpinners()
{
	$('select.chosen').chosen();
	$('select#new_grower').change(function(){
		reloadBoxes(curUrl,{week: $('input#selected_week_id').val(), grower:$(this).val()});
	})
	$('input.number').spinner();
	$('input.currency, input.decimal').spinner({
		places:2,
		step:.05
	});
}
loadSpinners();

function reloadBoxes(url,data)
{
	var ajaxUpdate = ['current-boxes','grower-item-grid'];
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

$('div#inventory, form#box-item-form tbody').on('click', 'table a', function(){
	var $a = $(this);
	reloadBoxes($a.attr('href'),{});
	return false;
});

$('form#box-item-form').on('change', 'input, select', function(){
	$(this).parent('td').addClass('dirty');
});

$('form#box-item-form').on('submit', function() {

	$loading.show();
	var ajaxUpdate = ['current-boxes','grower-item-grid'];
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

$('.week-picker').datepicker({
	showOtherMonths: true,
	selectOtherMonths: true,
	numberOfMonths: 1,
	onSelect: function(dateText, inst) {
		var date = $(this).datepicker('getDate');
		
		//availableWeeks is a global variable found in the boxItem/create page
		$.each(availableWeeks, function(key,week){
			var parts = week['week_delivery_date'].split('-');
			var dateObj = new Date(parts[0],parts[1]-1,parts[2]);
			if (dateObj.getTime() == date.getTime()) {
				reloadBoxes(curUrl,{week: week['week_id']});
			}
		});
	},
	beforeShowDay: function(date) {
		
		var found = false;
		var weekNotes = null;
		var oSelDate = null;
		var cssClass = ''
		
		//selectedDate is a global variable found in the boxItem/create page
		if(selectedDate)
		{
			var selParts = selectedDate.split('-');
			oSelDate = new Date(selParts[0],selParts[1]-1,selParts[2]);
		}
		
		//availableWeeks is a global variable found in the boxItem/create page
		$.each(availableWeeks, function(key,week) {
			var parts = week['week_delivery_date'].split('-');
			
			if(week['week_notes'])
				weekNotes = week['week_notes'];
			else 
				weekNotes = null;
			
			var oWeekDate = new Date(parts[0],parts[1]-1,parts[2]);
			
			if(oSelDate && oSelDate.getTime() == date.getTime()) {
				cssClass = 'ui-state-active';
			}
			
			if (oWeekDate.getTime() == date.getTime()) {
				found=true;
				return false; //break out of each loop
			}
		});

		if(found)
			return [true,cssClass,weekNotes];
		else
			return [false,cssClass,weekNotes];

	}
});