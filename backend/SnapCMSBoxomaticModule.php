<?php
/**
 * @author Francis Beresford
 * @package snapcms.comments
 * Class SnapCMSCommentsModule
 */
class SnapCMSBoxomaticModule extends SnapCMSModule 
{
	public $name = 'Box-O-Matic';

	/**
	 * import classes
	 */
	public function init() 
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		Yii::app()->setModules(array(
			'gii' => array(
				'class' => 'system.gii.GiiModule',
				'password' => 'francis',
				'generatorPaths' => array(
					'bootstrap.gii',
					'application.gii',
				),
				//'modulePath'=>Yii::app()->basePath . '/modules/snapcms/modules/boats/',
				// If removed, Gii defaults to localhost only. Edit carefully to taste.
				'ipFilters' => array('127.0.0.1', '::1'),
			),
			'payPal'=>array(
				'class'=>'boxomatic.modules.payPal.PayPalModule',
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
		));

		// import the module-level models and components
		$this->setImport(array(
			'snapcms.modules.boxomatic.models.*',
			'snapcms.modules.boxomatic.components.*',
			'boxomatic.extensions.yii-mail.YiiMailMessage',
		));
		
		Yii::app()->setComponents(array(
			'mail' => array(
				'class' => 'boxomatic.extensions.yii-mail.YiiMail',
				'transportType' => 'php',
				'viewPath' => 'boxomatic.views.mail',
				'logging' => true,
				'dryRun' => false
			),
		));

		parent::init();
	}
}