$(document).ready(function(){
	$("input#BoxomaticUser_tag_names").tagit({
		allowSpaces: true,
		availableTags: availableTags,
		singleField: true
	});
});