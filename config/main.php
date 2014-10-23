<?php

//Can we "calculate" this?
define('SNAP_FRONTEND_URL', '');
define('SNAP_BACKEND_URL', '/admin');

// uncomment the following to define a path alias
Yii::setPathOfAlias('backend', '../backend');
Yii::setPathOfAlias('frontend', '../frontend');
Yii::setPathOfAlias('web', '../public_html');
Yii::setPathOfAlias('vendor', '../vendor');
Yii::setPathOfAlias('Omnipay', '../vendor/Omnipay/');
Yii::setPathOfAlias('Guzzle', '../vendor/Guzzle/');
Yii::setPathOfAlias('Symfony', '../vendor/Symfony/');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Box-o-Matic',
    'id' => 'snapcms',
    'theme' => 'boxomatic',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'backend.models.*',
        'backend.components.*',
        //Import these if you are using the bootstrap module
        'boxomatic.models.*',
        'boxomatic.components.*',
        'bootstrap.helpers.*',
        'bootstrap.behaviors.*',
        'boxomatic.extensions.yii-mail.YiiMailMessage',
        'frontend.components.*',
    ),
    'aliases' => array(
        //If you are using the bootstrap module,
        'bootstrap' => 'vendor.drmabuse.yii-bootstrap-3-module',
        'boxomatic' => 'frontend.modules.boxomatic',
    ),
    'modules' => array(
        'payPal' => array(
            'env' => 'sandbox', // 'sandbox' or '' for live 
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
        // uncomment the following to enable the Gii tool
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'francis',
            'generatorPaths' => array(
                'bootstrap.gii',
                'application.gii',
            ),
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
        ),
    ),
    // application components
    'components' => array(
        'mail' => array(
            'class' => 'boxomatic.extensions.yii-mail.YiiMail',
            'transportType' => 'php',
            'viewPath' => 'application.views.mail',
            //'logging' => true,
            //'dryRun' => false
        ),
        'authManager' => array(
            'class' => 'SnapAuthManager',
            'connectionID' => 'db',
            'defaultRoles' => array('Anonymous'),
        ),
        'user' => array(
            // enable cookie-based authentication
            'class' => 'backend.components.SnapWebUser',
        ),
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'class' => 'backend.components.SnapUrlManager',
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                //'/<path:\w+>'=>'/content/view',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CDbLogRoute',
                    'connectionID' => 'db',
                    'levels' => 'error, warning, info',
                    'logTableName' => '{{log}}'
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
    'params'=>array(
        'Pin'=>array(
            'secret_key'=>'nCf9Yl-dAVxv0hDL1hEw4A'
        )
    )
);
