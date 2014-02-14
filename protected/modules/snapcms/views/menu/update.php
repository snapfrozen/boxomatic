<?php
/* @var $this MenuController */
/* @var $model Menu */

$this->breadcrumbs=array(
	'Menus'=>array('admin'),
	'Update: '.$model->name,
);

$this->operations=array(
	array('label'=>'Add Menu Item', 'url'=>array('/snapcms/menuItem/create','menu'=>$model->id)),
	array('label'=>'View Menu', 'url'=>array('view', 'id'=>$model->id)),
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

<script type="text/javascript">
	SnapCMS.menuId = "<?php echo $model->id ?>";
</script>

<div class="page-header">
	<h1 class="text-muted">Update <?php echo $model->name; ?></h1>
</div>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>