<?php
$this->menu=array(
	array('label'=>'List User', 'url'=>array('index')),
	array('label'=>'Create User', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('user-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Users</h1>

<p>asdfsadf <?php echo CHtml::link('Create user',array('user/create')) ?></p>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'first_name',
		'last_name',
		'user_email',
		array(
			'name'=>'Customer.balance',
			'value'=>'$data->Customer ? Yii::app()->snapFormat->currency($data->Customer->balance) : ""',
		),
		'last_login_time',
		array( 'name'=>'customer_id', 'value'=>'empty($data->customer_id) ? "No" : "Yes"'),
		array( 'name'=>'grower_id', 'value'=>'empty($data->grower_id) ? "No" : "Yes"'),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}{update}{delete}{login}',
			'buttons'=>array(
				'login' => array
				(
					'url'=> 'array("user/loginAs","id"=>$data->id)',
				),
			),
		),
	),
)); ?>
