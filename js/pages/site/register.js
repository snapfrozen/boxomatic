$(function(){
	$('#registration-form').find('fieldset').find('div.row:last-child').before(
		$('<div>').addClass('row').append(
			$('<div>').addClass('large-12 columns').append(
				$('<label>').attr('for', 'is_human').text("You're not a robot now, are you?").css('margin-bottom', '15px').prepend(
					$('<input>').attr({ type: 'checkbox', name : 'is_human', id : 'is_human', value : 1}).css('margin-right', '10px')
				)
			)
		)
	);
});