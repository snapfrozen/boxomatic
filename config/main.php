<?php
define('SNAP_FRONTEND_URL', '');
define('SNAP_BACKEND_URL', '/admin');

// uncomment the following to define a path alias
Yii::setPathOfAlias('backend','../backend');
Yii::setPathOfAlias('frontend','../frontend');
Yii::setPathOfAlias('web','../public_html');
Yii::setPathOfAlias('vendor','../vendor');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Website',
	'id'=>'snapcms',
	'theme'=>'boxomatic',

	// preloading 'log' component
	'preload'=>array('log'),
	
	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'backend.models.*',
		'backend.components.*',
		//Import these if you are using the bootstrap module
		'bootstrap.components.*',
		'bootstrap.helpers.*',
		'bootstrap.behaviors.*',
		'boxomatic.models.*',
		'boxomatic.components.*',
	),
	
	'aliases' => array(
		//If you are using the bootstrap module
		'bootstrap' => 'vendor.drmabuse.yii-bootstrap-3-module',
		'boxomatic' => 'frontend.modules.boxomatic.backend',
    ),

	'modules'=>array(
		//If you are using the bootstrap module
		'bootstrap' => array(
			'class' => 'bootstrap.BootStrapModule'
		),
		'shop' => array(
			'class' => 'application.modules.boxomatic.frontend.FrontendBoxomaticModule'
		),
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'francis',
			'generatorPaths' => array(
                'bootstrap.gii',
				'application.gii',
            ),
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
	),

	// application components
	'components'=>array(

		'authManager'=>array(
			'class'=>'SnapAuthManager',
			'connectionID'=>'db',
			'defaultRoles'=>array('Anonymous'),
		),
		
		'user'=>array(
			// enable cookie-based authentication
			'class'=>'backend.components.SnapWebUser',
		),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'class'=>'backend.components.SnapUrlManager',
			'urlFormat'=>'path',
			'showScriptName' => false,
			'rules'=>array(
				//'/<path:\w+>'=>'/content/view',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CDbLogRoute',
					'connectionID' => 'db',
					'levels'=>'error, warning, info',
					'logTableName'=>'{{log}}'
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
					'categories'=>'system.db.CDbCommand',
				),
				 */
			),
		),
	),
);
