<?php
/**
 * Add or override backend configuration here
 */
return array(
	'import'=>array(
		'boxomatic.models.*',
		'boxomatic.components.*',
	),
	'aliases' => array(
		'boxomatic' => 'frontend.modules.boxomatic.backend',
	),
	// application components
	'modules'=>array(
		'snapcms' => array(
			'class' => 'application.modules.snapcms.SnapCMSModule',
			'modules' => array (
				//Example module
				'boxomatic' => array (
					'class' => 'boxomatic.SnapCMSBoxomaticModule'
				)
			)
		),
	),
	'components'=>array(
		'db'=>require('../../db.php'),
	),
);
