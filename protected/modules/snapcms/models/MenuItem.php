<?php

/**
 * This is the model class for table "{{menu_items}}".
 *
 * The followings are the available columns in table '{{menu_items}}':
 * @property integer $id
 * @property string $path
 * @property string $title
 * @property integer $parent
 * @property integer $menu_id
 * @property integer $content_id
 * @property integer $sort
 * @property string $external_path
 * @property string $created
 * @property string $updated
 *
 * The followings are the available model relations:
 * @property Menus $menu
 * @property MenuItem $parent0
 * @property MenuItem[] $menuItems
 */
class MenuItem extends SnapActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{menu_items}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('path, title, menu_id', 'required'),
			array('content_id, sort, parent', 'numerical', 'integerOnly'=>true),
			array('path, title, external_path', 'length', 'max'=>255),
			array('menu_id', 'length', 'max'=>50),
			array('external_path,', 'url'),
			array('created, updated', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, path, title, parent, menu_id, external_path, created, updated', 'safe', 'on'=>'search'),
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
			'Content' => array(self::BELONGS_TO, 'Content', 'content_id'),
			'Parent' => array(self::BELONGS_TO, 'MenuItem', 'parent'),
			'children' => array(self::HAS_MANY, 'MenuItem', 'parent','order'=>'sort ASC'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'path' => 'Path',
			'title' => 'Title',
			'parent' => 'Parent',
			'menu_id' => 'Menu',
			'content_id' => 'Content',
			'sort' => 'Sort Index',
			'external_path' => 'External Path',
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
		$criteria->compare('path',$this->path,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('parent',$this->parent);
		$criteria->compare('menu_id',$this->menu_id);
		$criteria->compare('external_path',$this->external_path,true);
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
	 * @return MenuItem the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getMenuList($settings, $level=1) 
	{
		$returnarray = array(
			'label' => $this->title,
			//'url' => array('/content/view', 'path' => $this->path),
			'url' => $this->path,
			'itemOptions' => array('data-id'=>$this->id),
		);
		
		$subitems = array();
		if($this->children) 
		{
			/*
			//Duplicate first level link because of bootstrap click to activate links
			if($level==1) {
				$subitems []= $returnarray;
			}
			 */
			
			foreach($this->children as $child) {
				$subitems[] = $child->getMenuList($settings,$level+1);
			}
		}

		if($subitems !== array()) {
			$returnarray['items']=$subitems;
		}
		
		if($subitems !== array() && $level == 1) {
			array_unshift($subitems,$returnarray);
		}
		
		return $returnarray;
	}
	
	public function beforeSave()
	{
		$this->path = SnapFormat::slugify($this->path);
		return parent::beforeSave();
	}
	
	public function getMenu()
	{
		return new Menu($this->menu_id);
	}
}
