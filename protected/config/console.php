<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',
	
		// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.models.snap.*',
		'application.components.*',
		'application.components.snap.*',
		'application.modules.user.models.*',
		'ext.yii-mail.YiiMailMessage',
	),
	
	// application components
	'components'=>array(
		'db'=>array(
			'connectionString' => 'mysql:host=francis.centos;dbname=fbapp_foodbox',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'francis',
			'tablePrefix' => '',
			'charset' => 'utf8',
//			'enableProfiling' => true, //
//			'enableParamLogging' => true, //
		),
	),
	
		'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'password',
		 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),		
		
		'payPal'=>array(
			'env'=>'',
			'account'=>array(
				'username'=>'secretary_api1.northbankgarden.org.au',
				'password'=>'8NGRF6NB9CZVGQ9M',
				'signature'=>'AwKwAkKIBoRAzkQ2x1TE9DeYT9CxAA8nOeOXh3L0c9484nuuJ0Hd04uI',
				'email'=>'secretary@northbankgarden.org.au',
				'identityToken'=>null,
				//'identityToken'=>'YRkbQplC3JQjTdf_PaLnza2k2T5tZhQTGwtObQsenjQDxN07M5qLKKGNNu3u',
			),
		),
	),
);