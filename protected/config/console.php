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
		
		'mail' => array(
 			'class' => 'ext.yii-mail.YiiMail',
 			'transportType' => 'php',
 			'viewPath' => 'application.views.mail',
 			'logging' => true,
 			'dryRun' => false
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
		'orderDeadlineDays'=>6, //orders must be placed within 7 days of delivery 
		'deliveryDayOfWeek'=>3, //0 (for Sunday) through 6 (for Saturday)
		'autoCreateWeeks'=>24   //Amount of weeks to auto create boxes for in advance
	),
);