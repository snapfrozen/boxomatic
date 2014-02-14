<?php

/**
 * This is the model class for table "{{content}}".
 *
 * The followings are the available columns in table '{{content}}':
 * @property integer $id
 * @property string $title
 * @property string $type
 * @property boolean $published
 * @property string $created
 * @property string $updated
 */
class Content extends SnapActiveRecord
{
	const TYPE_MACHINE_NAME = 'content';
	const TYPE_FRIENDLY_NAME = 'Content';
	const FOREIGN_NAME = 'content_id';
	
	public $ContentType = null;  //ContentType Model
	//public $content_type = null; //For CGridView
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{content}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.

		return array(
			array('title, type', 'required'),
			array('title, type', 'length', 'max'=>255),
			array('published', 'boolean'),
			array('created, updated', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('content_type, id, title, type, created, updated', 'safe', 'on'=>'search'),
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
			//'ContentType' => array(self::HAS_ONE, 'ContentType', 'content_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'type' => 'Type',
			'published' => 'Published',
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
		//$criteria->with = 'ContentType';
		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('updated',$this->updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'updated DESC',
			)
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Content the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function __construct($scenario = 'insert') 
	{
		$this->ContentType = new ContentType;
		parent::__construct($scenario);
	}
	
	public function afterFind()
	{
		$cnfCTs = ContentType::getConfigArray();
		if(isset($cnfCTs[$this->type])) {
			$this->ContentType = new ContentType($cnfCTs[$this->type]);
		}
		$this->ContentType->content_id = $this->id;
		$this->ContentType->loadData();
		$this->ContentType->Content = $this;
		
		parent::afterFind();
	}
	
	public function setType($type)
	{
		$cnfCTs = ContentType::getConfigArray();
		$this->type = $type;
		if(isset($cnfCTs[$this->type])) {
			$this->ContentType = new ContentType($cnfCTs[$this->type]);
		}
	}

	/**
	 * @return \app\models\content\ContentType
	 */
	public function getContentType()
    {
        return ContentType::find($this->type);
    }
	
	public function getMenuItem($Menu)
	{
		$MI = MenuItem::model()->findByAttributes(array(
			'menu_id'=>$Menu->id,
			'content_id'=>$this->id,
		));
		if(!$MI)
		{
			$MI=new MenuItem;
			$MI->menu_id=$Menu->id;
			$MI->content_id=$this->id;
		}
		return $MI;
	}
	
	public function __get($name) 
	{	
		$ct = $this->ContentType;
		$attributes = $this->getAttributes();
		if($ct && isset($this->$name) && !array_key_exists($name, $attributes)) {
			return $ct->$name;
		} else {
			return parent::__get($name);
		}
	}
	
	public function __isset($name) 
	{
		$attributes = $this->getAttributes();
		if(isset($attributes[$name]) || isset($this->ContentType->$name))
			return true;
		else
			parent::__isset($name);
	}
}
