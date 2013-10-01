<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Box-o-Matic',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.models.snap.*',
		'application.components.*',
		'application.components.snap.*',
		'application.modules.user.models.*',
		'ext.yii-mail.YiiMailMessage',
		'ext.highcharts.*',
		'ext.CEditableGrid.*',
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
				'username'=>'Bellingengreengrocers_api1.westnet.com.au',
				'password'=>'N294GVZERL25SPP6',
				'signature'=>'An6uekYMEaLgi5oAJt8T2u-P-nGaAHNjDh1.PU5IACd0hEPHDdcEPgEt',
				'email'=>'Bellingengreengrocers@westnet.com.au',
				'identityToken'=>null,
                                /*
				'username'=>'secretary_api1.northbankgarden.org.au',
				'password'=>'8NGRF6NB9CZVGQ9M',
				'signature'=>'AwKwAkKIBoRAzkQ2x1TE9DeYT9CxAA8nOeOXh3L0c9484nuuJ0Hd04uI',
				'email'=>'secretary@northbankgarden.org.au',
				'identityToken'=>null,
                                */
				//'identityToken'=>'YRkbQplC3JQjTdf_PaLnza2k2T5tZhQTGwtObQsenjQDxN07M5qLKKGNNu3u',
			),
			/*
			'account'=>array(
				'username'=>'franci_1351410774_biz_api1.gmail.com',
				'password'=>'1351410806',
				'signature'=>'AJiMIo7kJww9KwPUOMqbTR3uuBvSAAUP0yxOYb6SRjZ.nQYBpmatKaZC',
				'email'=>'franci_1351410774_biz@gmail.com',
				'identityToken'=>null,
			),
			 */
			'components'=>array(
				'buttonManager'=>array(
					//'class'=>'payPal.components.PPDbButtonManager'
					'class'=>'payPal.components.PPPhpButtonManager',
				),
			),
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
		
		'phpThumb'=>array(
			'class'=>'ext.EPhpThumb.EPhpThumb',
		),

		'mail' => array(
 			'class' => 'ext.yii-mail.YiiMail',
 			'transportType' => 'php',
 			'viewPath' => 'application.views.mail',
 			'logging' => true,
 			'dryRun' => false
 		),

		'authManager'=>array(
			'class'=>'CDbAuthManager',
			'connectionID'=>'db',
			'itemTable'=>'auth_item',
			'itemChildTable'=>'auth_item_child',
			'assignmentTable'=>'auth_assignment',
		),
		
		'ePdf' => array(
			'class' => 'ext.yii-pdf.EYiiPdf',
			'params' => array(
				'mpdf' => array(
					'librarySourcePath' => 'application.external.mpdf.*',
					'constants'         => array(
						//'_MPDF_TEMP_PATH' => Yii::getPathOfAlias('application.runtime'),
					),
					'class'=>'mpdf', // the literal class filename to be loaded from the vendors folder
					/*'defaultParams'     => array( // More info: http://mpdf1.com/manual/index.php?tid=184
						'mode'              => '', //  This parameter specifies the mode of the new document.
						'format'            => 'A4', // format A4, A5, ...
						'default_font_size' => 0, // Sets the default document font size in points (pt)
						'default_font'      => '', // Sets the default font-family for the new document.
						'mgl'               => 15, // margin_left. Sets the page margins for the new document.
						'mgr'               => 15, // margin_right
						'mgt'               => 16, // margin_top
						'mgb'               => 16, // margin_bottom
						'mgh'               => 9, // margin_header
						'mgf'               => 9, // margin_footer
						'orientation'       => 'P', // landscape or portrait orientation
					)*/
				),
			),
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
//			'enableProfiling' => true, //
//			'enableParamLogging' => true, //
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
//				array(
//					'class'=>'CWebLogRoute',
//					'categories'=>'system.db.CDbCommand',
//					//'showInFireBug'=>true,
//				),
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
		'googleMapKey'=>'AIzaSyAU7aJq2EcQYJV7BsjZg1lkhR2dYBTZxfU',
		'adminEmail'=>'info@bellofoodbox.org.au',
		'adminEmailFromName'=>'Bellofoodbox',
		'months'=>array(
			1=>'January',
			2=>'February',
			3=>'March',
			4=>'April',
			5=>'May',
			6=>'June',
			7=>'July',
			8=>'August',
			9=>'September',
			10=>'October',
			11=>'November',
			12=>'December',
		),
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
			'EA'=>'Each',
			'BUNCH'=>'Per bunch',
			'KG'=>'Per kg',
		),
		'paymentTypes'=>array(
			'BT'=>'Bank Transfer',
			'CASH'=>'Cash',
			'PAYPAL'=>'PayPal',
		),
		'orderDeadlineDays'=>4, //orders must be placed within 7 days of delivery 
		'deliveryDayOfWeek'=>1, //0 (for Sunday) through 6 (for Saturday)
		'autoCreateWeeks'=>24   //Amount of weeks to auto create boxes for in advance
	),
);
