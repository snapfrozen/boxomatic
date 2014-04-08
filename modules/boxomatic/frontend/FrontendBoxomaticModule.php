<?php
/**
 * @author Francis Beresford
 * @package snapcms.comments
 * Class SnapCMSCommentsModule
 */
class FrontendBoxomaticModule extends CWebModule 
{
	//public $name = 'Box-O-Matic Frontend';

	/**
	 * import classes
	 */
	public function init() 
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		Yii::app()->setModules(array(
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
		));

		// import the module-level models and components
		Yii::app()->setImport(array(
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