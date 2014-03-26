<?php
/**
 * @author Francis Beresford
 * @package snapcms.comments
 * Class SnapCMSCommentsModule
 */
class FrontendBoxomaticModule extends CWebModule 
{
	//public $name = 'Box-O-Matic Frontend';

	/**
	 * import classes
	 */
	public function init() 
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		Yii::app()->setModules(array(
	
		));

		// import the module-level models and components
		$this->setImport(array(
		
		));
		
		Yii::app()->setComponents(array(

		));

		parent::init();
	}
}