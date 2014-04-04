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
					'username'=>'franci_1351410774_biz_api1.gmail.com',
					'password'=>'1351410806',
					'signature'=>'AJiMIo7kJww9KwPUOMqbTR3uuBvSAAUP0yxOYb6SRjZ.nQYBpmatKaZC',
					'email'=>'franci_1351410774_biz@gmail.com',
					'identityToken'=>null,
				),
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
		Yii::app()->setImport(array(
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