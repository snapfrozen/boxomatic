<?php
Yii::import('zii.widgets.CDetailView');
class SnapDetailView extends CDetailView
{
	public $htmlOptions = array('class'=>'table');
//	public $summaryCssClass = 'panel-heading';
	public $cssFile = false;
//	public $itemsCssClass = 'items table';
}