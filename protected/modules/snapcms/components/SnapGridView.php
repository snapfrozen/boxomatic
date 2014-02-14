<?php
Yii::import('zii.widgets.grid.CGridView');
class SnapGridView extends CGridView
{
	public $htmlOptions = array('class'=>'panel panel-default');
	public $summaryCssClass = 'panel-heading';
	public $cssFile = false;
	public $itemsCssClass = 'items table';
	public $pagerCssClass = 'pagination-wrapper';
	public $pager = array(
		'header'=>'',
		'cssFile'=>false,
		'hiddenPageCssClass'=>'disabled',
		'selectedPageCssClass'=>'active',
		'htmlOptions'=>array('class'=>'pagination'),
	);
}