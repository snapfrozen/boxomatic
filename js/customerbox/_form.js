var $BoxId = $('#CustomerBox_box_id');
$Quantity = $('#CustomerBox_quantity');

function selectBox(id)
{
	var boxId = $.fn.yiiGridView.getSelection(id);
	var settings = $.fn.yiiGridView.settings[id];
	var url = $.fn.yiiGridView.getUrl(id);
	
	$('#'+id).addClass(settings.loadingClass);
	
	var ajaxUpdate = ['selected-box'];
	$.ajax({
		type: 'GET',
		url: url,
		data: {
			boxId:boxId[0],
			quantity:$Quantity.val()
		},
		success: function(data,status) {
			$.each(ajaxUpdate, function(i,v) {
				var id='#'+v;
				$(id).replaceWith($(id,'<div>'+data+'</div>'));
			});
			$('#'+id).removeClass(settings.loadingClass);
			$.fn.yiiGridView.selectCheckedRows(id);
		}
	});
	
	$BoxId.val(boxId);
}

$Quantity.change(function(){
	selectBox('box-sizes-grid');
});