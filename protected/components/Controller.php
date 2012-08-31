<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	
	/**
	 * Set default user states so the application won't crash
	 * when trying to access these properies and they don't exist
	 */
	public function init() 
	{	
		if( !Yii::app()->user->hasState('customer_id') )
			Yii::app()->user->setState('customer_id', false);
		if( !Yii::app()->user->hasState('grower_id') )
			Yii::app()->user->setState('grower_id', false);
	}
}