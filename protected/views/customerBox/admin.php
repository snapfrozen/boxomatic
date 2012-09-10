<?php
$this->menu=array(
	array('label'=>'List CustomerBox', 'url'=>array('index')),
	array('label'=>'Create CustomerBox', 'url'=>array('create')),
);
?>

<h1>Manage Customer Boxes</h1>

<?php 

$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'customer-box-grid',
	'dataProvider'=>$model->search(),
//	'filter'=>$model,
	'columns'=>array(
		array(
			'class'=>'CLinkColumn',
			'header'=>User::model()->getAttributeLabel('first_name'),
			'labelExpression'=>'$data->Customer->User->first_name',
			'urlExpression'=>'Yii::app()->createUrl("user/view",array("id"=>$data->Customer->User->id))',
			'visible'=>Yii::app()->user->checkAccess("admin"),
		),
		'Box.BoxSize.box_size_name',
		'quantity',
		'total_price',
		array(
			'name'=>'Box.week_id',
			'value'=>'Yii::app()->dateFormatter->format("EEE, MMM d",$data->Box->Week->week_delivery_date)',
		),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); 

?>
