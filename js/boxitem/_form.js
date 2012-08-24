$('input.number').spinner();
$('input.currency, input.decimal').spinner({
	places:2,
	step:.05
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
				window.location.href = curUrl + '&week=' + week['week_id'];
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