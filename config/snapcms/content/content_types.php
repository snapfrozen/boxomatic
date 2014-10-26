<?php
return array(
	'homepage' => array (
		'id' => 'homepage',
		'name' => 'Homepage',
		'description' => '',
		'show_in_cms' => false,
		'auto_add_to_menu' => array(),
		'fields' => array(
			'content_1' => 'text',
			'content_2' => 'text',
			'content_3' => 'text',
			'content_4' => 'text',
			'content_5' => 'text',
			'meta_keywords' => 'string',
			'meta_description' => 'string',
		),
		'rules' => array (
			array('content_1, content_2, content_3, content_4, content_5', 'safe'),
		),
		'groups' => array (
			'Content' => array('content_1', 'content_2', 'content_3', 'content_4', 'content_5'),
			'SEO' => array('meta_keywords','meta_description'),
		),
		'input_types' => array (
			'meta_description' => 'textAreaControlGroup',
		)
	),
	'page' => array (
		'id' => 'page',
		'name' => 'Page',
		'description' => 'A standard page',
		'show_in_cms' => true,
		'auto_add_to_menu' => array('main_menu'),
		'fields' => array(
			'content' => 'text',
			'meta_keywords' => 'string',
			'meta_description' => 'string',
		),
		'rules' => array (
			array('content', 'length', 'max'=>255),
		),
		'groups' => array (
			'Content' => array('content'),
			'SEO' => array('meta_keywords','meta_description'),
		),
		'input_types' => array (
			'meta_description' => 'textAreaControlGroup',
		)
	),
	'news_list' => array (
		'id' => 'news_list',
		'name' => 'News List',
		'description' => '',
		'show_in_cms' => false,
		'auto_add_to_menu' => array(),
		'fields' => array(
			'content' => 'text',
			'meta_keywords' => 'string',
			'meta_description' => 'string',
		),
		'rules' => array (
			array('content', 'length', 'max'=>255),
		),
		'groups' => array (
			'Content' => array('content'),
			'SEO' => array('meta_keywords','meta_description'),
		),
		'input_types' => array (
			'meta_description' => 'textAreaControlGroup',
		)
	),
	'news' => array (
		'id' => 'news',
		'name' => 'News',
		'description' => 'News items will appear in the news section of your website.',
		'show_in_cms' => true,
		'auto_add_to_menu' => array(),
		'fields' => array(
			'content' => 'text',
			'intro' => 'text',
			'meta_keywords' => 'string',
			'meta_description' => 'string',
			'image' => 'string',
			'file' => 'string',
		),
		'rules' => array (
			array('file, meta_keywords, meta_description', 'length', 'max'=>255),
			array('file', 'file'),
			array('image', 'file', 'types'=>'jpg, jpeg, gif, png'),
			array('content, intro', 'safe'),
		),
		'groups' => array (
			'Content' => array('intro','content','image','file'),
			'SEO' => array('meta_keywords','meta_description'),
		),
		'input_types' => array (
			'image' => 'imageField',
			'file' => 'fileField',
			'intro' => array(
				'widget'=> array(
					'class' => 'vendor.ckeditorwidget.TheCKEditorWidget',
					'settings' => SnapUtil::config('content.ckeditor/plain'),
				),
			),
			'meta_description' => 'textAreaControlGroup',
		)
	),
);