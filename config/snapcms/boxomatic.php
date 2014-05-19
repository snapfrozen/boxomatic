<?php
return array(
	'tablePrefix'=>'boxo_',
	'minimumCredit'=>-100,
	// this is used in contact page
	'googleMapKey'=>'AIzaSyAU7aJq2EcQYJV7BsjZg1lkhR2dYBTZxfU',
	'adminEmail'=>'francis@snapfrozen.com.au',
	'adminEmailFromName'=>'Box-o-Matic',
	'mailChimpApiKey'=>'0560b57c64ce08accd22aebbfae2102d-us3',
	'mailChimpListId'=>'ff830a357f',
	'supplier_product_root_id'=>1,
	'supplier_product_feature_category'=>2,
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
	'orderDeadlineDays'=>4, //orders must be placed within 7 days of delivery 
	'deliveryDateLocations'=>array(
		'Monday'=>array(1),
		'Tuesday'=>array(2),
		'Wednesday'=>array(),
		'Thursday'=>array(),
		'Friday'=>array(1,2),
		'Saturday'=>array(),
		'Sunday'=>array(),
	), //0 (for Sunday) through 6 (for Saturday)
	'autoCreateDeliveryDates'=>24, //Amount of weeks to auto create boxes for in advance
	'defaultImages'=>array(
		'BoxItem'=>'frontend/data/boxitem/default.jpg',
		'BoxSize'=>'frontend/data/boxsize/default.jpg',
	)
);
