<?php
/* @var $this CategoryController */
/* @var $Category Category */

$this->breadcrumbs=array(
	'Box-O-Matic'=>array('/snapcms/boxomatic/index'),
	'Suppliers'=>array('supplier/admin'),
	'Supplier Products'=>array('supplierProduct/admin'),
	'Categories'
);

$this->menu=array(
	array('icon' => 'glyphicon-plus-sign','label'=>'Create Category', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search',
    "
        $('.search-form form').submit(function(){
            $('#category-grid').yiiGridView('update', {
            data: $(this).serialize()
        });
        return false;
    });"
);

$this->page_heading = 'Categories';
?>
<?php $this->beginWidget('bootstrap.widgets.BsPanel', array(
	'title'=>BsHtml::button('Advanced search',array(
		'data-toggle' => 'collapse',
		'data-target' => '#search-form',
		'class' =>'search-button', 
		'icon' => BsHtml::GLYPHICON_SEARCH,
		'color' => BsHtml::BUTTON_COLOR_PRIMARY), '#'),
));
?>	<div id="search-form" class="search-form collapse">
		<?php $this->renderPartial('_search',array(
			'Category'=>$Category,
		)); ?>
	</div><!-- search-form -->

	<?php $this->widget('application.widgets.SnapGridView',array(
	'id'=>'category-grid',
	'dataProvider'=>$Category->search(),
	'filter'=>$Category,
	'columns'=>array(
			'parent:number',
		array(
				'name'=>'name',
				'type'=>'raw',
				'value'=>'CHtml::link($data->name, array("update","id"=>$data->id))',
			),	array(
	'class'=>'bootstrap.widgets.BsButtonColumn',
	),
	),
	)); ?>
<?php $this->endWidget(); ?>