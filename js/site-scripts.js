jQuery.fn.toggleSection=function( options ) {  

	var doToggle=function(){
		var $header=$(this).parent();
		var $content=$header.siblings('div.toggleSectionContent').eq(0);
		if($header.hasClass('open')) {
			$content.stop(true,true);
			$content.slideUp(function(){
				$content.removeClass('open');
				$header.removeClass('open');
			});
			$header.find('span').html('[+]');
		} else {
			$content.stop(true,true);
			$content.slideDown();
			$header.addClass('open');
			$content.addClass('open');
			$header.find('span').html('[-]');
		}
		return false;
	}

	return this.each(function() {
		var $header=$(this).find('span');
		$header.unbind('click');
		$header.click(doToggle);
	});

};
$(document).foundation();
$(document).ready(function(){
	$('select.chosen').chosen();
	$('h3.toggle, h4.toggle, span.toggle').toggleSection();
});