<?php
/* @var $this MenuController */
/* @var $model Menu */

$this->breadcrumbs=array(
	'Menus',
);
?>

<div class="page-header">
	<h1 class="text-muted">Menus</h1>
</div>

<table class="items table">
	<thead>
		<tr>
			<th>Name</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach(Menu::getMenus() as $menu) : ?>	
		<tr>
			<td><?php echo $menu->name ?></td>
			<td><?php echo CHtml::link('update',array('/snapcms/menu/update','id'=>$menu->id)); ?></td>
		</tr>		
	<?php endforeach; ?>
	</tbody>
</table>
