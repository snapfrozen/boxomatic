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
            /*
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
             */
            'payPal' => array(
                'class' => 'frontend.modules.payPal.PayPalModule',
                'env' => '',
                'account' => array(
                    'username' => 'franci_1351410774_biz_api1.gmail.com',
                    'password' => '1351410806',
                    'signature' => 'AJiMIo7kJww9KwPUOMqbTR3uuBvSAAUP0yxOYb6SRjZ.nQYBpmatKaZC',
                    'email' => 'franci_1351410774_biz@gmail.com',
                    'identityToken' => null,
                ),
                'components' => array(
                    'buttonManager' => array(
                        //'class'=>'payPal.components.PPDbButtonManager'
                        'class' => 'payPal.components.PPPhpButtonManager',
                    ),
                ),
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
            'ePdf' => array(
                'class' => 'boxomatic.extensions.yii-pdf.EYiiPdf',
                'params' => array(
                    'mpdf' => array(
                        'librarySourcePath' => 'boxomatic.external.mpdf.*',
                        'constants' => array(
                        //'_MPDF_TEMP_PATH' => Yii::getPathOfAlias('application.runtime'),
                        ),
                        'class' => 'mpdf', // the literal class filename to be loaded from the vendors folder
                    /* 'defaultParams'     => array( // More info: http://mpdf1.com/manual/index.php?tid=184
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
                      ) */
                    ),
                ),
            ),
        ));

        parent::init();
    }

}
