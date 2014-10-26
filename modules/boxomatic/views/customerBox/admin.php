<?php
$this->menu=array(
	array('label'=>'List UserBox', 'url'=>array('index')),
	array('label'=>'Create UserBox', 'url'=>array('create')),
);
?>

<h1>Manage Customer Boxes</h1>

<?php 

$this->widget('bootstrap.widgets.BsGridView', array(
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
			'name'=>'Box.delivery_date_id',
			'value'=>'Yii::app()->dateFormatter->format("EEE, MMM d",$data->Box->DeliveryDate->date)',
		),
		array(
			'class'=>'bootstrap.widgets.BsButtonColumn',
		),
	),
)); 

?>
