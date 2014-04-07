<?php
return array (
	'varchar(%)' => 'TextField',
	'text' => array(
		//'fieldType' => 'TextArea',
		'widget'=> array(
			'class' => 'vendor.ckeditorwidget.TheCKEditorWidget',
			'settings' => SnapUtil::config('content.ckeditor/default'),
		)
	),
	'datetime' => array(
		'widget' => array(
			'class'=>'zii.widgets.jui.CJuiDatePicker',
			'settings'=>array(),
		)
	),
	'string' => array (
		'fieldType' => 'TextFieldControlGroup',
	),
);