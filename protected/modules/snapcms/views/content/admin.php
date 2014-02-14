<?php
/* @var $this ContentController */
/* @var $model Content */

$this->breadcrumbs=array(
	'Contents'=>array('index'),
	'Manage',
);

$this->operations=array(
	array('label'=>'Create Page', 'url'=>array('/snapcms/contentType/index')),
);
?>

<div class="page-header">
	<h1 class="text-muted">Manage Content</h1>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'content-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'cssFile' => '', 
	'columns'=>array(
		array(
			'name'=>'title',
			'type'=>'raw',
			//'header'=>'Name',
			'value'=>'CHtml::link($data->title, array("/snapcms/content/update","id"=>$data->id))',
		),
		array(
			'name'=>'type',
			//'header'=>'Content Type',
			'filter'=>ContentType::getList(),
		),
		array(            // display 'create_time' using an expression
            'name'=>'updated',
            'value'=>'SnapFormat::date($data->updated)',
        ),
		array(
			'class'=>'application.components.snap.SnapButtonColumn',
			'viewButtonUrl'=>'array("/content/view/","id"=>$data->id)',
		),
	),
)); ?>
