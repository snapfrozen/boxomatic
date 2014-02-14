<?php
/**
 * 
 */
class Menu extends CModel
{
	protected $rootMenuItems;
	public $name;
	public $id;
			
	public function __construct($id) 
	{
		$cnf = SnapUtil::getConfig('general');
		$this->id = $id;
		$this->name = $cnf['menus'][$id];
	
		$criteria = new CDbCriteria();
		$criteria->addCondition('menu_id=:id AND (parent=0 OR parent IS NULL)');
		$criteria->order = 'sort';
		$criteria->params = array(':id'=>$id);
		
		$items = MenuItem::model()->findAll($criteria);
		$this->rootMenuItems = $items ? $items : array(); //If no items found make sure this is an array
	}

	public function attributeNames() 
	{
		return array();
	}
	
	/**
	 * @param array $settings
	 * admin => true or false
	 * @return type 
	 */
	public function getMenuList($settings=array()) 
	{
		$subitems = array();
		if($this->rootMenuItems) 
		{
			foreach($this->rootMenuItems as $child) {
				$subitems[] = $child->getMenuList($settings);
			}
		}
		return $subitems;
	}
	
	public static function getMenus()
	{
		$cnf = SnapUtil::getConfig('general');
		$menus = array();
		foreach($cnf['menus'] as $key=>$menuName) {
			$menus[] = self::model($key);
		}
		return $menus;
	}
	
	public function getItemDropDownList()
	{
		$data = $this->_recurItemDropDownList($this->rootMenuItems);
		$rootobj = new MenuItem;
		$rootobj->id = null;
		$rootobj->title = $this->name;
		$root = array($rootobj);
		$data = array_merge($root, $data);

		return CHtml::listData($data,'id','title');
	}
	
	private function _recurItemDropDownList($data, $pos=1)
	{
		foreach($data as $child) 
		{
			$child->title = str_repeat(" - ", $pos)  . $child->title;
			$data = array_merge($data, $this->_recurItemDropDownList($child->children, $pos+1));
		}
		return $data;
	}
	
	public static function getDropDownList()
	{
		return CHtml::listData(self::getMenus(),'id','name');
	}
	
	/**
	 * 
	 * @param type $items 
	 */
	public function updateStructure($items, $parent=null)
	{
		$pos=0;
		foreach($items as $item)
		{
			$MI = MenuItem::model()->findByPk($item['id']);
			$MI->parent = $parent;
			$MI->sort = $pos++;
			var_dump($MI->save());
			
			if(isset($item['children'])) {
				//print_r($items);
				$this->updateStructure($item['children'], $MI->id);
			}
		}
	}
		
	public static function model($menuName, $className=__CLASS__)
	{
		return new $className($menuName);
	}
}
