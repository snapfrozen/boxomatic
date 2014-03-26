<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class BoxomaticController extends Controller
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
	 * @var string home label for the secondary nav
	 */
	public $nav_brand_label=false;
	/**
	 * @var mixed Yii url for secondary nav home label.
	 */
	public $nav_brand_url=array('/snapcms/boxomatic');
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $secondary_menu=array(
		array('label'=>'Boxes','url'=>array('boxItem/create'),
			'items'=>array(
				array('label'=>'Box Packing','url'=>array('boxItem/create')),
				array('label'=>'Box Sizes','url'=>array('boxSize/admin')),
				array('label'=>'Packing Stations','url'=>array('packingStation/admin')),
				array('label'=>'Delivery Dates','url'=>array('deliveryDate/admin')),
			)
		),
		array('label'=>'Customers','url'=>array('/user/customers'),
			'items'=>array(
				array('label'=>'Customers','url'=>array('user/customers')),
				array('label'=>'Orders','url'=>array('boxItem/userBoxes')),
				array('label'=>'Payments','url'=>array('userPayment/enterPayments')),
				array('label'=>'Location','url'=>array('location/admin')),
			)
		),
		array('label'=>'Suppliers','url'=>array('supplier/admin'),
			'items'=>array(
				array('label'=>'Suppliers','url'=>array('supplier/admin')),
				array('label'=>'Products','url'=>array('supplierProduct/admin')),
				array('label'=>'Orders','url'=>array('supplierPurchase/admin')),
				array('label'=>'Supplier Map','url'=>array('supplier/map')),
			)
		),
		array('label'=>'Inventory','url'=>array('inventory/index'),
			'items'=>array(
				array('label'=>'Inventory','url'=>array('inventory/index')),
				array('label'=>'Log','url'=>array('inventory/admin')),
			)
		),
		array('label'=>'Reports','url'=>'#',
			'items'=>array(
				array('label'=>'Credit','url'=>array('report/creditReport')),
				array('label'=>'Box Sales','url'=>array('report/salesReport')),
			)
		),
	);

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
		$cs=Yii::app()->clientScript;
		$baseUrl = $this->createFrontendUrl('/');
		$cs->registerCssFile($baseUrl.'/themes/boxomatic/admin/css/admin.css');
		$this->scriptLocations[Yii::app()->basePath . '/../public_html/themes/boxomatic/admin/'] = $this->createFrontendUrl('/').'/themes/boxomatic/admin/';
		
		$this->nav_brand_label = CHtml::image('/themes/boxomatic/images/cog-leaf.png');
		if( !Yii::app()->user->hasState('user_id') )
			Yii::app()->user->setState('user_id', false);
		if( !Yii::app()->user->hasState('supplier_id') )
			Yii::app()->user->setState('supplier_id', false);
		if( !Yii::app()->user->hasState('shadow_id') )
			Yii::app()->user->setState('shadow_id', false);
		if( !Yii::app()->user->hasState('shadow_name') )
			Yii::app()->user->setState('shadow_name', false);
		
		//Test if the login key find the user and auto login.
		$key=Yii::app()->request->getParam('key');
		if($key)
		{
			$User=User::model()->findByAttributes(array('auto_login_key'=>$key),'update_time > date_sub(NOW(), interval 7 day)');
			if($User)
			{
				$identity=new UserIdentity($User->email,'');
				$identity->authenticate(false);
				Yii::app()->user->login($identity);
				
				$User->auto_login_key='';
				$User->save(false);
			}
			//exit;
		}
	}
}