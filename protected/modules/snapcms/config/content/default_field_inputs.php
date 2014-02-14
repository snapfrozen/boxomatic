<?php
$conf = SnapUtil::getConfig('content.ckeditor');
return array (
	'varchar(%)' => 'TextField',
	'text' => array(
		//'fieldType' => 'TextArea',
		'widget'=> array(
			'class' => 'application.extensions.CKEditor.TheCKEditorWidget',
			'settings' => $conf['default'],
		)
	),
	'datetime' => array(
		'widget' => array(
			'class'=>'zii.widgets.jui.CJuiDatePicker',
			'settings'=>array(),
		)
	),
	'string' => array (
		'fieldType' => 'TextField',
	),
);