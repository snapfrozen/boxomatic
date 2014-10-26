//$('select.chosen').chosen();

function updateSortableList()
{
	$("div.sortable ul li").each(function() {
		if($(this).find('> ul').length == 0) {
			$(this).append('<ul></ul>');
		}
		var $emptyUls = $(this).find("ul").filter(function() {
			return $.trim($(this).text()) === "";
		});

		$(this).find('ul.empty').removeClass('empty');
		$emptyUls.addClass('empty');
	});
}
updateSortableList();

function buildTreeList($elem)
{
	var list = [];
	$elem.find('> li').each(function(){

		var item = {};
		item.id = $(this).data('id');

		if($(this).find('> ul > li').length > 0) {
			item.children = buildTreeList($(this).find('> ul'));
		}
		
		list.push(item);
		
	});	
	return list;
}

var lastPos=-1;
$("div.sortable ul").sortable({
	connectWith: "div.sortable ul",
	placeholder: "ui-state-highlight",
	containment: "div.sortable",
	start: function(event, ui) {
		ui.placeholder.html(ui.item.clone().html());
	},
	change: function(event, ui) {
		if(ui.placeholder.parents('ul').length != lastPos) {
			$(ui.placeholder).after(ui.item);
			lastPos = ui.placeholder.parents('ul').length;
		}
	},
	update: function(event, ui) {
		if (this === ui.item.parent()[0]) {
			updateSortableList();
			var idList = buildTreeList($("div.sortable ul").eq(0));
			console.log(idList);
			$.ajax({
				type:"POST",
				data:{
					items:idList
				},
				url:SnapCMS.baseUrl + "/snapcms/menu/updateStructure/id/" + SnapCMS.menuId,
				success:function(){

				}
			});
		}
	}
});

$("div.sortable ul").eq(0).css("min-height",$("div.sortable ul").height());