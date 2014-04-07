<?php
$baseUrl = SNAP_FRONTEND_URL;
return array(
	'default'=>array(
		//'attribute'=>'content',     
		'height'=>'400px',
		'width'=>'100%',
		'toolbarSet'=>array(
			array(
				'name'=>'paragraph',
				'items'=>array('NumberedList','BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'Blockquote')
			),
			array(
				'name'=>'links',
				'items'=>array('Link','Unlink','Anchor')
			),
			array(
				'name'=>'links',
				'items'=>array('Image','Table','HorizontalRule')
			),
			'/',
			array(
				'name'=>'styles',
				'items'=>array('Format')
			),
			array(
				'name'=>'basicstyles',
				'items'=>array('Bold','Italic','Underline')
			),
			array(
				'name'=>'clipboard',
				'items'=>array('Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo')
			),
			array(
				'name'=>'tools',
				'items'=>array('Maximize')
			),
		),
		'ckeditor'=>Yii::getPathOfAlias('backend.external.CKeditor').'/ckeditor.php',
		'ckBasePath'=>$baseUrl.'/lib/ckeditor/',
		//'css' => $baseUrl.'/css/index.css',
		'config'=>array(
			'filebrowserBrowseUrl'=> $baseUrl.'/lib/kcfinder/browse.php?type=files',
			'filebrowserImageBrowseUrl'=> $baseUrl.'/lib/kcfinder/browse.php?type=images',
			'filebrowserFlashBrowseUrl'=> $baseUrl.'/lib/kcfinder/browse.php?type=flash',
			'filebrowserUploadUrl'=> $baseUrl.'/lib/kcfinder/upload.php?type=files',
			'filebrowserImageUploadUrl'=> $baseUrl.'/lib/kcfinder/upload.php?type=images',
			'filebrowserFlashUploadUrl'=> $baseUrl.'/lib/kcfinder/upload.php?type=flash'
		),
	),
	'plain'=>array(
		//'attribute'=>'content',     
		'height'=>'150px',
		'width'=>'100%',
		'toolbarSet'=>array(
			array(
				'name'=>'basicstyles',
				'items'=>array('Bold','Italic','Underline')
			),
		),
		'ckeditor'=>Yii::getPathOfAlias('backend.external.CKeditor').'/ckeditor.php',
		'ckBasePath'=>$baseUrl.'/lib/ckeditor/',
		//'css' => $baseUrl.'/css/index.css',
		'config'=>array(
			'filebrowserBrowseUrl'=> $baseUrl.'/lib/kcfinder/browse.php?type=files',
			'filebrowserImageBrowseUrl'=> $baseUrl.'/lib/kcfinder/browse.php?type=images',
			'filebrowserFlashBrowseUrl'=> $baseUrl.'/lib/kcfinder/browse.php?type=flash',
			'filebrowserUploadUrl'=> $baseUrl.'/lib/kcfinder/upload.php?type=files',
			'filebrowserImageUploadUrl'=> $baseUrl.'/lib/kcfinder/upload.php?type=images',
			'filebrowserFlashUploadUrl'=> $baseUrl.'/lib/kcfinder/upload.php?type=flash'
		),
	),
);
