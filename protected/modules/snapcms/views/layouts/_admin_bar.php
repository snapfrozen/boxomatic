<?php 
$app=Yii::app();
$baseUrl = Yii::app()->request->baseUrl;

//var_dump($this->module);
if($app->user->checkAccess('Admin') && !$this->module) :
	$cs = $app->clientScript;
	$conf = SnapUtil::getConfig('content.ckeditor');

	$toolbar = $conf['default']['toolbarSet'];
	$fileBrowser = $conf['default']['config'];
	$cnfStr='';
	foreach($fileBrowser as $key=>$val){
		$cnfStr.='editor.config.'.$key.' = "'.$val.'";'."\n";
	}

	//$cs->registerCssFile($adminThemeUrl . '/css/admin-bar.css');
	$cs->registerScriptFile($baseUrl . '/lib/ckeditor/ckeditor.js', CClientScript::POS_END);
	$cs->registerScript('CKEditor Inline',"
		\$saveButton = $('div#admin-nav a#ckSave');
		CKEDITOR.on( 'instanceCreated', function( event ) {
		
			var editor = event.editor,
			element = editor.element;

			editor.on( 'configLoaded', function() {
				// Remove unnecessary plugins to make the editor simpler.
				editor.config.toolbar = " . json_encode($toolbar) . ";
				" . $cnfStr . "
			});
			
			editor.on('change', function(){
				\$saveButton.removeClass('snap-disabled');
			});

		});
	", CClientScript::POS_END);
	$cs->registerScriptFile($baseUrl . '/js/admin.js', CClientScript::POS_END);
	?>

	<div id="admin-nav">
		<ul class="left">
			<?php if(isset($this->Content)): ?>
			<li><?php echo CHtml::link('Edit',array('/snapcms/content/update/','id'=>$this->Content->id)); ?></li>			
			<li><?php echo CHtml::link('Save','javascript:void(0)',array('class'=>'snap-disabled snap-btn snap-btn-default','id'=>'ckSave'));?></li>
			<?php endif; ?>
		</ul>
	</div>

<?php endif; ?>