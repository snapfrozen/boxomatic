
$(document).ready(function(){

	//Save Content on the page
	$('div#admin-nav a#ckSave').click(function(){
		var $a = $(this);
		var origText = $a.html();

		for(var name in CKEDITOR.instances) 
		{
			var editor = CKEDITOR.instances[name];
			if(!editor.checkDirty())
				continue;

			$a.html('Saving...')
				.addClass('snap-processing');

			var data = editor.getData();
			var htmlElem = CKEDITOR.instances[name].element.$;
			
			var fieldName = name.substr(6);
			var postData = {
				ContentType: {},
				ajax:'content-form'
			};
			postData.ContentType[fieldName]=data;

			$.ajax({
				type:"POST",
				async:false,
				url: SnapCMS.baseUrl + '/snapcms/content/update/id/' + $(htmlElem).data('id'),
				data:postData,
				success:function(data) {
					//@TODO:Move to "Saved!" to template so it can be translated
					$a.html('Saved!')
						.removeClass('snap-processing')
						.addClass('snap-success');
						
					editor.resetDirty();
					
					setTimeout(function(){
						$a.html(origText)
							.removeClass('snap-success')
							.addClass('snap-disabled');
					},3000);
				}
			});
		}
	});
	
	
});

function beforeUnload( e )
{
	for(var name in CKEDITOR.instances) 
	{
		if ( CKEDITOR.instances[name].checkDirty() )
			return e.returnValue = "You will lose the changes you have made to the page.";
	}
}
if ( window.addEventListener )
    window.addEventListener( 'beforeunload', beforeUnload, false );
else
    window.attachEvent( 'onbeforeunload', beforeUnload );