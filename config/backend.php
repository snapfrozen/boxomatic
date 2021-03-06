<?php

/**
 * Add or override backend configuration here
 */
return array(
    'import' => array(
        'boxomatic.models.*',
        'boxomatic.components.*',
    ),
    'aliases' => array(
        'boxomatic' => 'frontend.modules.boxomatic',
    ),
    // application components
    'modules' => array(
        'snapcms' => array(
            'class' => 'backend.components.SnapCMSModule',
            'modules' => array(
                //Example module
                'boxomatic' => array(
                    'class' => 'boxomatic.SnapCMSBoxomaticModule'
                )
            )
        ),
    ),
    'components' => array(
        'db' => require(__DIR__.'/../../db.php'),
    /*
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
      array(
      'class'=>'CWebLogRoute',
      'categories'=>'system.db.CDbCommand',
      ),
      ),
      ),
     */
    ),
);
