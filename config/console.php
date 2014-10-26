<?php
define('SNAP_FRONTEND_URL', '');
define('SNAP_BACKEND_URL', '/admin');

// uncomment the following to define a path alias
Yii::setPathOfAlias('backend','../backend');
Yii::setPathOfAlias('frontend','../frontend');
Yii::setPathOfAlias('vendor','../vendor');

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',

	// preloading 'log' component
	'preload'=>array('log'),
	
	'import'=>array(
		'backend.models.*',
		'backend.components.*',
		'application.models.*',
		'application.components.*',
		'bootstrap.components.*',
		'snapcms.models.*',
		'snapcms.components.*',
	),
	
	'aliases' => array(
		'bootstrap' => 'application.modules.bootstrap',
		'snapcms' => 'application.modules.snapcms',
    ),

	// application components
	'components'=>array(
		'db'=>require('../db.php'),
		// uncomment the following to use a MySQL database
		/*
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=testdrive',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
		*/
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
);