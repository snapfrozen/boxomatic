<?php

/**
 * This is the model class for table "box_sizes".
 *
 * The followings are the available columns in table 'box_sizes':
 * @property integer $box_sizes
 * @property string $box_size_name
 * @property string $box_size_value
 * @property string $box_size_markup
 * @property string $box_size_price
 *
 * The followings are the available model relations:
 * @property Boxes[] $boxes
 */
class BoxSize extends BoxomaticActiveRecord
{
	static $imageDir = 'data/boxsizes';
	static $defaultImageLocation = 'data/boxsizes/default.gif';
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BoxSize the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return $this->tablePrefix . 'box_sizes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('box_size_name, box_size_value, box_size_markup, box_size_price', 'length', 'max'=>45),
			array('image_ext', 'length', 'max'=>20),
			array('image', 'file', 'types'=>'jpg, gif, png', 'allowEmpty'=>true),
			array('description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('box_sizes, box_size_name, box_size_value, box_size_markup, box_size_price', 'safe', 'on'=>'search'),
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
			'boxes' => array(self::HAS_MANY, 'Boxes', 'size_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'box_size_name' => 'Name',
			'box_size_value' => 'Value',
			'box_size_markup' => 'Markup',
			'box_size_price' => 'Price',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('box_size_name',$this->box_size_name,true);
		$criteria->compare('box_size_value',$this->box_size_value,true);
		$criteria->compare('box_size_markup',$this->box_size_markup,true);
		$criteria->compare('box_size_price',$this->box_size_price,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function beforeSave()
	{	
		$dataDir = Yii::getPathOfAlias('backend.data');
		
		$field='image';
		$uploadFile=CUploadedFile::getInstance($this,$field);
		
		if(!$uploadFile) 
			return parent::beforeSave();

		$this->$field=$uploadFile;
		$dirPath=$dataDir.'/'.strtolower(__CLASS__);
		if (!file_exists($dirPath)) {
			mkdir($dirPath, 0777, true);
		}

		if(!$this->$field || !$this->$field->saveAs($dirPath.'/'.$field.'_'.$this->id))
			Yii::app()->user->setFlash('danger','problem saving image for field: '.$field);
		
		return parent::beforeSave();
	}
}