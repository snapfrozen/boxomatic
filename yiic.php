<?php

// change the following paths if necessary
$yiic=dirname(__FILE__).'/../vendor/yiisoft/yii/framework/yiilite.php';
//$config=dirname(__FILE__).'/config/console.php';

$config = CMap::mergeArray(
        require(__FILE__ . '/../backend/config/main.php'),
        require(__FILE__ . '/config/main-local.php')
);


require_once($yiic);
