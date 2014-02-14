<?php
/**
 * @var yii\base\View $this
 */
//$this->title = 'Welcome';
$this->breadcrumbs=array(
	'Menu List',
);

//$this->operations=array(
//	array('label'=>'Status', 'url'=>array('status')),
//);
?>
<div class="page-header">
	<h1 class="text-muted">Create Page</h1>
</div>

<div class="">
	<div class="panel-heading">Content Types</div>
	
	<table class="table table-striped table-hover">
		<tr>
			<td>Name</td>
		</tr>
		<?php foreach ($data as $ct): ?>
		<tr>
			<td><?php echo CHtml::link($ct->name, array('/snapcms/content/create', 'type'=>$ct->id)); ?></td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>

