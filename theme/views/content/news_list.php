<?php
/* @var $this ContentController */
/* @var $model Content */
$this->pageTitle=Yii::app()->name . ' - ' . $Content->title;
$this->breadcrumbs = Menu::model('main_menu')->getBreadcrumbTrail($MenuItem);
$items = Menu::model('main_menu')->getMenuList($MenuItem,2);
$this->menu = $items;
if(empty($this->menu)) {
	$this->layout='//layouts/column1';
}

$dataProvider=new CActiveDataProvider('Content',array(
    'criteria'=>array(
        'condition'=>'type="news"',
        'order'=>'created DESC',
    ),
	'pagination'=>array(
        'pageSize'=>10,
    ),
));
?>

<div class="page-header">
	<h1 class="text-muted"><?php echo $Content->title ?></h1>
</div>
<?php echo SnapHtml::editableArea($Content, 'content', $this->isEditable()) ?>
<?php $this->widget('bootstrap.widgets.BsListView', array(
	'dataProvider'=>$dataProvider,
	'id'=>'news-list',
	'itemView'=>'/content/_news_view',
)); ?>