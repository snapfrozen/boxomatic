$('select#grower_id').change(function(){
	var url = $('input#grower_update_url').val();
	var $growerItems = $('select#GrowerPurchase_grower_item_id');
	
	$.ajax({
		type: 'GET',
		url: url,
		dataType:'json',
		data: {
			growerId:$(this).val()
		},
		success: function(data,status) {
			$growerItems.html(' ');
			$growerItems.append('<option value=""> - Select - </option>');
			$.each(data, function(i,v) {
				$growerItems.append('<option value="' + i + '">' + v + '</option>');
			});
			$growerItems.trigger("liszt:updated");
		}
	});
});