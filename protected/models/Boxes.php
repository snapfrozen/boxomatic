<?php

/**
 * This is the model class for table "boxes".
 *
 * The followings are the available columns in table 'boxes':
 * @property integer $box_id
 * @property integer $size_id
 * @property string $box_price
 * @property integer $week_id
 *
 * The followings are the available model relations:
 * @property BoxItems[] $boxItems
 * @property BoxSizes $size
 * @property Weeks $week
 * @property CustomerBoxes[] $customerBoxes
 */
class Boxes extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Boxes the static model class
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
		return 'boxes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('size_id, week_id', 'numerical', 'integerOnly'=>true),
			array('box_price', 'length', 'max'=>7),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('box_id, size_id, box_price, week_id', 'safe', 'on'=>'search'),
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
			'BoxItems' => array(self::HAS_MANY, 'BoxItems', 'box_id'),
			'BoxSize' => array(self::BELONGS_TO, 'BoxSize', 'size_id'),
			'Week' => array(self::BELONGS_TO, 'Week', 'week_id'),
			'CustomerBoxes' => array(self::HAS_MANY, 'CustomerBox', 'box_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'box_id' => 'Box',
			'size_id' => 'Size',
			'box_price' => 'Box Price',
			'week_id' => 'Week',
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

		$days=Yii::app()->params['orderDeadlineDays'];
		
		$criteria=new CDbCriteria;
		
		$criteria->with = array('Week');
		$criteria->addCondition("date_sub(Week.week_delivery_date, interval $days day) > NOW()");
		
		$criteria->compare('box_id',$this->box_id);
		$criteria->compare('size_id',$this->size_id);
		$criteria->compare('box_price',$this->box_price,true);
		$criteria->compare($this->getTableAlias(false, false) . '.week_id',$this->week_id);
		$criteria->order='Week.week_delivery_date';

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}