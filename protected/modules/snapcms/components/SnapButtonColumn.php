<?php
Yii::import('zii.widgets.grid.CButtonColumn');
class SnapButtonColumn extends CButtonColumn
{
	public $deleteButtonImageUrl = false;
	public $deleteButtonOptions = array('class'=>'btn btn-xs btn-danger');
	public $updateButtonImageUrl = false;
	public $updateButtonOptions = array('class'=>'btn btn-xs btn-primary');
	public $viewButtonImageUrl = false;
	public $viewButtonOptions = array('class'=>'btn btn-xs btn-info');
}