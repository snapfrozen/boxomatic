<?php
return array(
	'page' => array (
		'id' => 'page',
		'name' => 'Page',
		'fields' => array(
			'content' => 'text',
			'meta_keywords' => 'string',
			'meta_description' => 'string',
		),
		'rules' => array (
			array('content', 'safe'),
			array('meta_keywords', 'length', 'max'=>255),
			array('meta_description', 'length', 'max'=>255),
		),
		'inputTypes' => array (
			'file' => 'fileField',
			'meta_description' => 'textArea',
		)
	),
	'news' => array (
		'id' => 'news',
		'name' => 'News',
		'fields' => array(
			'content' => 'text',
			'image' => 'string',
			//'file' => 'string',
			'meta_keywords' => 'string',
			'meta_description' => 'string',
		),
		'rules' => array (
			array('meta_keywords, meta_description', 'length', 'max'=>255),
			//array('file', 'file'),
			array('image', 'file', 'types'=>'jpg, jpeg, gif, png'),
			array('content', 'safe'),
		),
		'inputTypes' => array (
			'image' => 'imageField',
			'file' => 'fileField',
			//'content' => 'textArea',
		)
	),
	'home' => array (
		'id' => 'home',
		'name' => 'Home Page',
		'fields' => array(
			'content_1' => 'text',
			'content_2' => 'text',
			'content_3' => 'text',
			'content_4' => 'text',
			'content_5' => 'text',
		),
		'rules' => array (
			array('content_1, content_2, content_3, content_4, content_5', 'safe'),
		),
		'inputTypes' => array (
			'file' => 'fileField',
//			'content_1' => 'textArea',
//			'content_2' => 'textArea',
//			'content_3' => 'textArea',
//			'content_4' => 'textArea',
//			'content_5' => 'textArea',
		)
	),
);