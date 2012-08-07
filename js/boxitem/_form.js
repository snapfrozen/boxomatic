var $ItemName = $('#BoxItem_item_name');
var $ItemUnit = $('#BoxItem_item_unit');
var $ItemGrower = $('#BoxItem_grower_id');
var $ItemGrowerName = $('#grower_name');
var $ItemQuantity = $('#BoxItem_item_quantity');
var $ItemId = $('#BoxItem_box_item_id');

var $ItemValue = $('#BoxItem_item_value');
var $ItemBox = $('#BoxItem_box_id');
var $submitButton = $('#addToBox');

var boxSizesGridId = 'box-sizes-grid';
var weekGridId = 'week-grid';
var growerItemGridId = 'grower-item-grid';
var currentItemsGridId = 'current-items-grid';

$submitButton.click(function(){
	var weekId = $.fn.yiiGridView.getSelection(weekGridId);
	var sizeId = $.fn.yiiGridView.getSelection(boxSizesGridId);

	var url = $('#box-item-form').attr('action') + '&weekId=' + weekId + '&sizeId=' + sizeId;;
	var data = $('#box-item-form').serialize();

	var ajaxUpdate = ['current-box','BoxItem_box_id','totals'];
	$.ajax({
		type: 'POST',
		url: url,
		data: data,
		success: function(data,status) {
			$.each(ajaxUpdate, function(i,v) {
				var id='#'+v;
				$(id).replaceWith($(id,'<div>'+data+'</div>'));
			});
			updateSubmitButton();
		}
	});

	return false;
});

function changeBoxItem(id)
{
	if(id == currentItemsGridId)
		$('#' + growerItemGridId).find('tr.selected').removeClass('selected');
	else if(id == growerItemGridId)
		$('#' + currentItemsGridId).find('tr.selected').removeClass('selected');

	var $gridTable = $('#'+id+' tbody');
	var $selectedRow = $gridTable.find('> tr.selected');
	var selectedIndex = $gridTable.find('> tr').index($selectedRow);
	var cells = $.fn.yiiGridView.getRow(id,selectedIndex);

	var itemId = '';
	var growerId = cells[0].className.replace('grower-','');
	var growerName = cells[0].innerHTML;
	var itemName = cells[1].innerHTML;
	var itemValue = cells[2].innerHTML;
	var itemUnit = cells[3].innerHTML;
	var itemQuantity = 1;

	if(id == currentItemsGridId) 
	{
		itemQuantity = cells[4].innerHTML;
		//set the box_item_id to update instead of create a new one
		itemId = $.fn.yiiGridView.getSelection(id);
	}

	$ItemId.val(itemId);
	$ItemName.val(itemName);
	$ItemUnit.val(itemUnit);
	$ItemValue.val(itemValue);
	$ItemGrower.val(growerId);
	$ItemGrowerName.val(growerName);
	$ItemQuantity.val(itemQuantity);


	updateSubmitButton();
}

function selectBox(id)
{
	reloadBox();
}

function deleteBoxItem()
{
	var deleteUrl = $(this).attr('href') + '&ajax=1';

	$.ajax({
		type: 'POST',
		url: deleteUrl,
		success: function() {
			reloadBox();
		}
	});

	return false;
}

function reloadBox()
{
	var settings = $.fn.yiiGridView.settings[currentItemsGridId];
	var weekId = $.fn.yiiGridView.getSelection(weekGridId);
	var sizeId = $.fn.yiiGridView.getSelection(boxSizesGridId);

	var url = $.fn.yiiGridView.getUrl(currentItemsGridId) + '&weekId=' + weekId + '&sizeId=' + sizeId;

	$('#'+currentItemsGridId).addClass(settings.loadingClass);
	var ajaxUpdate = ['current-box','BoxItem_box_id','totals'];
	$.ajax({
		type: 'GET',
		url: url,
		success: function(data,status) {
			$.each(ajaxUpdate, function(i,v) {
				var id='#'+v;
				$(id).replaceWith($(id,'<div>'+data+'</div>'));
			});
			$('#'+currentItemsGridId).removeClass(settings.loadingClass);
			$.fn.yiiGridView.selectCheckedRows(currentItemsGridId);

			updateSubmitButton();
		}
	});
}

function updateSubmitButton()
{
	var weekId = $.fn.yiiGridView.getSelection(weekGridId);
	var sizeId = $.fn.yiiGridView.getSelection(boxSizesGridId);
	var growerId = $ItemGrower.val();

	if(weekId.length && sizeId.length && growerId)
		$submitButton.removeAttr('disabled');
}