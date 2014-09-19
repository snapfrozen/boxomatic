$('select#supplier_id').change(function(){
	var url = $('input#update_url').val();
	var $supplierProducts = $('select#Inventory_supplier_product_id');
	
	$.ajax({
		type: 'GET',
		url: url,
		dataType:'json',
		data: {
			supplierId:$(this).val()
		},
		success: function(data,status) {
			$supplierProducts.html(' ');
			$supplierProducts.append('<option value=""> - Select - </option>');
			$.each(data, function(i,v) {
				$supplierProducts.append('<option value="' + i + '">' + v + '</option>');
			});
			$supplierProducts.trigger("liszt:updated");
		}
	});
});