<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'box o matic',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.models.snap.*',
		'application.components.*',
		'application.components.snap.*',
		'application.modules.user.models.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'password',
		 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),		
       
        'registration' => array(),
        'profile' => array(
        'privacySettingTable' => 'privacysetting',
        'profileFieldTable' => 'profile_field',
        'profileTable' => 'profile',
        'profileCommentTable' => 'profile_comment',
        'profileVisitTable' => 'profile_visit', 
        ),
	),
                
   
	// application components
	'components'=>array(
		
		'snap'=>array(
			'class'=>'SnapEncrypt',
			'salt'=>'x,QN,,Z@f~MD!9fpVV6AlED#=S:Pa:I!_?b1F{7fz7&5H'
		),
		
		'snapFormat'=>array(
			'class'=>'SnapFormat',
		),

		'authManager'=>array(
			'class'=>'CDbAuthManager',
			'connectionID'=>'db',
			'itemTable'=>'auth_item',
			'itemChildTable'=>'auth_item_child',
			'assignmentTable'=>'auth_assignment',
		),
		
		// uncomment the following to enable URLs in path-format
		/*
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		*/
		
		//'db'=>array(
		//	'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		//),
		// uncomment the following to use a MySQL database
	
		'db'=>array(
			'connectionString' => 'mysql:host=francis-laptop;dbname=foodbox',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'localdev',
			'tablePrefix' => '',
			'charset' => 'utf8',
		),
		        
        'user' => array(
			// enable cookie-based authentication
			'allowAutoLogin' => true,
			//'class' => 'RWebUser', // Allows super users access implicitly.
		),
		
		//'cache' => array('class' => 'system.caching.CDummyCache'),
	
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'francis.beresford@gmail.com',
		'states'=>array(
			''=>' - Select - ',
			'ACT'=>'Australian Capital Territory',
			'NSW'=>'New South Wales',
			'NT'=>'Northern Territory',
			'QLD'=>'Queensland',
			'SA'=>'South Australia',
			'TAS'=>'Tasmania',
			'VIC'=>'Victoria',
			'WA'=>'Western Australia',
		),
		'itemUnits'=>array(
			'KG' => 'Per kg',
	        'EA' => 'Each',
			'BUNCH' => 'Per bunch',
		)
	),
);