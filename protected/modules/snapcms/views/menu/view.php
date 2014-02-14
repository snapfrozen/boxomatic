<?php
/* @var $this MenuController */
/* @var $model Menu */

$this->breadcrumbs=array(
	'Menus'=>array('admin'),
	'View: ' . $model->name,
);

$this->operations=array(
	array('label'=>'Create Menu', 'url'=>array('create')),
	array('label'=>'Update Menu', 'url'=>array('update', 'id'=>$model->id)),
	array(
		'label'=>'Delete Menu', 
		'url'=>'#', 
		'linkOptions'=>array(
			'submit'=>array('delete','id'=>$model->id),
			'confirm'=>'Are you sure you want to delete this menu?',
			'class'=>'text-danger',
		),
	),
);
?>

<div class="page-header">
	<h1 class="text-muted"><?php echo $model->name; ?></h1>
</div>

<?php
	$this->beginWidget('zii.widgets.CPortlet', array(
		'title'=>$model->name,
		'titleCssClass' => 'panel-title',
		'decorationCssClass' => 'panel-heading',
		'htmlOptions'=>array('class'=>'panel panel-info')
	));
	$this->widget('zii.widgets.CMenu', array(
		'encodeLabel'=>false,
		'items'=>$model->menuList,
		'htmlOptions'=>array('class'=>'nav nav-stacked'),
	));
	$this->endWidget();
?>
