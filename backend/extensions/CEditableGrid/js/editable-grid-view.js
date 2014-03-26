(function ($) {
	var methods, hideInput, updateInput, initInputs, initFields, gridSettings = [];

	methods = {
		init: function (options) {
			//remove bind that $.yiigridview added which was intended for filters in the thead
			
			var settings=options;
			
			return this.each(function () {
			var $grid = $(this),
				id = $grid.attr('id');
				gridSettings[id]=settings;
				
				initFields($grid);
				
//				$grid.find("tbody input, tbody select").unbind("change");
//				$grid.find("tbody input, #tbody select").unbind("blur");
				
				$("body").on("submit","form.egv-form-add",function(){
					var $grid=$("#"+id);
					var $form=$(this);
					$.ajax({
						type:"POST",
						data:$form.serialize(),
						url:$form.attr("action"),
						dataType:"json",
						success:function(data){ 
							var settings=$.fn.yiiactiveform.getSettings($grid);
							$.each(settings.attributes, function() {
								hasError=$.fn.yiiactiveform.updateInput(this, data, $grid);
							});
							//update if there were no errors
							if(data.length==0) {
								$.fn.yiiGridView.update(id);
							}
						},
						error:function(data){ $input.addClass("error");	}
					});
					return false;
				});
				
				//var options=$.parseJSON($grid.find("div.egv-options").html());
				//$grid.yiiactiveform(options);
				
			});
		},
		refresh: function(options) {
			return this.each(function () {
				var $grid = $(this);
				initFields($grid);
			});
		}
	},
	
	initFields = function($grid) {
		var id = $grid.attr('id');
		$grid.find("tbody tr:not(.quickBar) input:not([type='hidden'],[type='checkbox']), tbody tr:not(.quickBar) select").each(function(){
			var $input=$(this);
			$input.after("<span></span>");
			hideInput($input);
		});
		$("#"+id+" tbody tr:not(.quickBar) td").click(function(){
			var $td=$(this);
			$td.find("span").hide();
			$td.find("input, select").show();
			$td.find("input, select").bind("input, change",function(){
				$(this).addClass("changed");
			});
			$td.find("input, select").focus();
		});

		$("#"+id+" tbody tr:not(.quickBar) input[type='text'], #"+id+" tbody tr:not(.quickBar) select").blur(function(){
			updateInput($grid, $(this));
		});
		$("#"+id+" tbody tr:not(.quickBar) input[type='checkbox'], #"+id+" tbody tr:not(.quickBar) select").change(function(){
			updateInput($grid, $(this));
		});
		var options=$.parseJSON($grid.find("div.egv-options").html());
		$grid.yiiactiveform(options);
		//console.log(options);
	},

	hideInput = function($input) {
		if($input.is("select")) {
			$input.next("span").html($input.find(":selected").text());
		} else if($input.is("[type='checkbox']")) {
			//do nothing
			return;
		} else {
			$input.next("span").html($input.val());
		}
		$input.hide();
		$input.next("span").show();
	},
	
	updateInput = function($grid, $input) {
		var id=$grid.attr('id');
		if(!$input.hasClass("changed")) {
			hideInput($input);
			return;
		}
		var $tr=$input.parents("tr").first();
		var trIndex=$grid.find("tbody tr").index($tr);
		var modelId=$grid.find(".keys span").eq(trIndex).html();
		if(!modelId) 
			return;
		var data=$tr.find("input, select").serializeArray();
		data.ajax=id;
		$input.closest("div").addClass("validating");

		$.ajax({
			type:"POST",
			data:data,
			url:gridSettings[id].updateUrl+"&id="+modelId,
			dataType:"json",
			success:function(data){ 
				$input.closest("div").removeClass("validating");
				var hasError=false;
				var settings=$.fn.yiiactiveform.getSettings($grid);
				$.each(settings.attributes, function() {
					e=$.fn.yiiactiveform.updateInput(this, data, $grid);
					if(e==true) hasError=e;
				});
				if(!hasError) {
					hideInput($input);
					$input.removeClass("changed");
				}
			},
			error:function(data){ $input.addClass("error");	}
		});
	},

	$.fn.yiiEditableGridView = function (method) {
		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		} else {
			$.error('Method ' + method + ' does not exist on jQuery.yiiEditableGridView');
			return false;
		}
	};

})(jQuery);

/*

function setActiveForm() {
	var $grid=$("#'.$id.'");
	var options=$.parseJSON($grid.find("div.egv-options").html());
	$grid.yiiactiveform(options);
	initInputs();
}
function updateInput() {
	var $grid=$("#'.$id.'");
	var $input=$(this);
	if(!$input.hasClass("changed")) {
		hideInput($input);
		return;
	}
	var $tr=$input.parents("tr").first();
	var trIndex=$grid.find("tbody tr").index($tr);
	var modelId=$grid.find(".keys span").eq(trIndex).html();
	if(!modelId) 
		return;
	var data=$tr.find("input, select").serializeArray();
	data.ajax="'.$this->id.'";
	$input.closest("div").addClass("validating");
	$.ajax({
		type:"POST",
		data:data,
		url:"' . $controller->createUrl($controller->getId() . '/' . $this->quickUpdateAction) . '&id=" + modelId,
		dataType:"json",
		success:function(data){ 
			$input.closest("div").removeClass("validating");
			var hasError=false;
			var settings=$.fn.yiiactiveform.getSettings($grid);
			$.each(settings.attributes, function() {
				e=$.fn.yiiactiveform.updateInput(this, data, $grid);
				if(e==true) hasError=e;
			});
			if(!hasError) {
				hideInput($input);
				$input.removeClass("changed");
			}
		},
		error:function(data){ $input.addClass("error");	}
	});
}
function initInputs() {
	//remove bind that $.yiigridview added which was intended for filters in the thead
	$("#'.$id.' tbody input, #'.$id.' tbody select").unbind("change");
	$("#'.$id.' tbody input, #'.$id.' tbody select").unbind("blur");

	$("#'.$id.' tbody tr:not(.quickBar) input:not([type=\'hidden\'],[type=\'checkbox\']), #'.$id.' tbody tr:not(.quickBar) select").each(function(){
		var $input=$(this);
		$input.after("<span></span>");
		hideInput($input);
	});
}
function hideInput($input) {
	if($input.is("select")) {
		$input.next("span").html($input.find(":selected").text());
	} else if($input.is("[type=\'checkbox\']")) {
		//do nothing
		return;
	} else {
		$input.next("span").html($input.val());
	}
	$input.hide();
	$input.next("span").show();
}

$("#'.$id.'").yiiactiveform('.$options.');
initInputs();

$("body").on("click", "#'.$id.' tbody tr:not(.quickBar) td", function(){
	$td=$(this);
	$td.find("span").hide();
	$td.find("input, select").show();
	$td.find("input, select").bind("input, change",function(){
		$(this).addClass("changed");
	});
	$td.find("input, select").focus();
});

$("body").on("blur", "#'.$id.' tbody tr:not(.quickBar) input[type=\'text\'], #'.$id.' tbody tr:not(.quickBar) select", updateInput);
$("body").on("change", "#'.$id.' tbody tr:not(.quickBar) input[type=\'checkbox\'], #'.$id.' tbody tr:not(.quickBar) select", updateInput);

$("body").on("submit","form.egv-form-add",function(){
	var $grid=$("#'.$id.'");
	$form=$(this);
	$.ajax({
		type:"POST",
		data:$form.serialize(),
		url:$form.attr("action"),
		dataType:"json",
		success:function(data){ 
			var settings=$.fn.yiiactiveform.getSettings($grid);
			$.each(settings.attributes, function() {
				hasError=$.fn.yiiactiveform.updateInput(this, data, $grid);
			});
			//update if there were no errors
			if(data.length==0) {
				$.fn.yiiGridView.update("'.$id.'");
			}
		},
		error:function(data){ $input.addClass("error");	}
	});
	return false;
});

*/