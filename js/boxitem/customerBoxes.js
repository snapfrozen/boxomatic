var $loading=$('span.loading');
$loading.css({display:'inline-block'});
$loading.hide();

function reloadCustomers(url,data)
{
	var ajaxUpdate = ['customerList'];
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
			$loading.hide();
		}
	});
}

$('body').on('click','#process',function(){return confirm('Are you sure?');});

$('.delivery-date-picker').datepicker({
	showOtherMonths: true,
	selectOtherMonths: true,
	numberOfMonths: 1,
	onSelect: function(dateText, inst) {
		var date = $(this).datepicker('getDate');
		
		//availableDeliveryDates is a global variable found in the boxItem/create page
		$.each(availableDeliveryDates, function(key,date){
			var parts = date['date'].split('-');
			var dateObj = new Date(parts[0],parts[1]-1,parts[2]);
			if (dateObj.getTime() == date.getTime()) {
				reloadCustomers(curUrl,{date: date['id']});
			}
		});
	},
	beforeShowDay: function(date) {
		
		var found = false;
		var notes = null;
		var oSelDate = null;
		var cssClass = ''
		
		//selectedDate is a global variable found in the boxItem/create page
		if(selectedDate)
		{
			var selParts = selectedDate.split('-');
			oSelDate = new Date(selParts[0],selParts[1]-1,selParts[2]);
		}
		
		//availableDeliveryDates is a global variable found in the boxItem/create page
		$.each(availableDeliveryDates, function(key,date) {
			var parts = date['date'].split('-');
			
			if(date['notes'])
				notes = date['notes'];
			else 
				notes = null;
			
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
			return [true,cssClass,notes];
		else
			return [false,cssClass,notes];

	}
});