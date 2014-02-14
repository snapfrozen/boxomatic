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
				window.location.href=curUrl+'/'+curDate['id']
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