<?php

/**
 * This is the model class for table "{{menus}}".
 *
 * The followings are the available columns in table '{{menus}}':
 * @property integer $id
 * @property string $name
 * @property string $created
 * @property string $updated
 *
 * The followings are the available model relations:
 * @property MenuItems[] $menuItems
 */
class Menu extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{menus}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('name', 'length', 'max'=>255),
			array('created, updated', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, created, updated', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'menuItems' => array(self::HAS_MANY, 'MenuItem', 'menu_id'),
			'rootMenuItems' => array(self::HAS_MANY, 'MenuItem', 'menu_id','condition'=>'parent=0 OR parent IS NULL', 'order'=>'sort ASC'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'created' => 'Created',
			'updated' => 'Updated',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('updated',$this->updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Menu the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function getDropDownList()
	{
		return CHtml::listData(self::model()->findAll(),'id','name');
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
	
	/**
	 * 
	 * @param type $items 
	 */
	public function updateStructure($items, $parent=null)
	{
		$pos=0;
		foreach($items as $item)
		{
			if($item['id'] == 12) {
				print_r($item);
			}
			$MI = MenuItem::model()->findByPk($item['id']);
			$MI->parent = $parent;
			$MI->sort = $pos++;
			$MI->save();
			
			if(isset($item['children'])) {
				print_r($items);
				$this->updateStructure($item['children'], $MI->id);
			}
		}
	}
}
