<?php

/**
 * This is the model class for table "categories".
 *
 * The followings are the available columns in table 'categories':
 * @property integer $id
 * @property integer $parent
 * @property string $name
 * @property string $description
 *
 * The followings are the available model relations:
 * @property Category $parent0
 * @property Category[] $categories
 */
class Category extends BoxomaticActiveRecord
{
	const boxCategory = 'box';
	const uncategorisedCategory = 'uncategorised';
	
	public function behaviors()
	{
		return array(
			'activerecord-relation'=>array(
				'class'=>'boxomatic.extensions.active-relation-behavior.EActiveRecordRelationBehavior',
			)
		);
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return $this->tablePrefix . 'categories';
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
			array('parent', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>100),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, parent, name, description', 'safe', 'on'=>'search'),
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
			'parent' => array(self::BELONGS_TO, 'Category', 'parent'),
			'children' => array(self::HAS_MANY, 'Category', 'parent', 'order'=>'name'),
			'SupplierProducts' => array(self::MANY_MANY, 'SupplierProduct', SnapUtil::config('boxomatic/tablePrefix').'supplier_product_categories(supplier_product_id,category_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'parent' => 'Parent',
			'name' => 'Name',
			'description' => 'Description',
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
		$criteria->compare('parent',$this->parent);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Category the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @param type $parentId The position to start descending down the tree from
	 * @param ActiveRecord $model The foreign model to retrieve selected data from
	 */
	public static function getCategoryTreeForm($parentId, $model) 
	{
		$selectedCategories = CHtml::listData($model->Categories,'id','id');
		$CatCurrent = Category::model()->findByPk($parentId);
		$output = '';
		$children = $CatCurrent ? $CatCurrent->children : array();
		foreach($children as $Cat)
		{
			$output .= '<li>';
			$output .= CHtml::checkBox(
				'Categories['.$Cat->id.']',
				in_array($Cat->id, $selectedCategories)
			); 
			$output .= CHtml::label($Cat->name, 'Categories_'.$Cat->id); 
			if(!empty($Cat->children)) {
				$output .= '<ul>';
				$output .= self::getCategoryTreeForm($Cat->id, $model);
				$output .= '</ul>';
			}
			$output .= '</li>';
		}
		return $output;
	}
	
	public static function getCategoryTree($parentId, $route, $selected = false)
	{
		$CatCurrent = Category::model()->findByPk($parentId);
		$output = '';
		if(!$CatCurrent) {
			return $output;
		}
		foreach($CatCurrent->children as $Cat)
		{
			$output .= '<li class="'. ($selected==$Cat->id?'selected':'') .'">';
			$output .= CHtml::link($Cat->name, $route + array('cat'=>$Cat->id)); 
			if(!empty($Cat->children)) {
				$output .= '<ul>';
				$output .= self::getCategoryTree($Cat->id, $route, $selected);
				$output .= '</ul>';
			}
			$output .= '</li>';
		}
		return $output;
	}
	
	public static function getDropDownList($start=null)
	{
		$items = array();
		if(!$start) {
			$rootItems = self::model()->findAll('parent=0 OR parent is null');
			$rootobj = new Category;
			$rootobj->id = 0;
			$rootobj->name = 'Categories';
			
		} else {
			$rootItems = self::model()->findAll('parent=:parentId',array(
				':parentId'=>$start
			));
			$rootobj = Category::model()->findByPk($start);
		}
		$root = array($rootobj);
		self::_recurDropDownList($rootItems, $items);
		
		$items = array_merge($root, $items);

		return CHtml::listData($items,'id','name');
	}
	
	public static function _recurDropDownList($data, &$items, $pos=1)
	{
		foreach($data as $child) 
		{
			$child->name = str_repeat(" - ", $pos)  . $child->name;
			$items[] = $child;
			self::_recurDropDownList($child->children, $items, $pos+1);
		}
		return $data;
	}
}
