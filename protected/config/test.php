<?php

return CMap::mergeArray(
	require(dirname(__FILE__).'/main.php'),
	array(
		'components'=>array(
			'fixture'=>array(
				'class'=>'system.test.CDbFixtureManager',
			),
			'db'=>array(
				'connectionString' => 'mysql:host=francis-laptop;dbname=foodbox_test',
				'emulatePrepare' => true,
				'username' => 'root',
				'password' => 'localdev',
				'charset' => 'utf8',
			),
		),
	)
);
