$(document).ready(function(){
	$("input#Customer_tag_names").tagit({
		allowSpaces: true,
		availableTags: availableTags,
		singleField: true
	});
});