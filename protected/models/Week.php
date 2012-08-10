<?php

/**
 * This is the model class for table "weeks".
 *
 * The followings are the available columns in table 'weeks':
 * @property integer $week_id
 * @property string $week_delivery_date
 * @property string $week_notes
 *
 * The followings are the available model relations:
 * @property Boxes[] $boxes
 */
class Week extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Week the static model class
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
		return 'weeks';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('week_delivery_date, week_notes', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('week_id, week_delivery_date, week_notes', 'safe', 'on'=>'search'),
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
			'Boxes' => array(self::HAS_MANY, 'Box', 'week_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'week_id' => 'Week',
			'week_delivery_date' => 'Week Starting',
			'week_notes' => 'Week Notes',
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

		$criteria=new CDbCriteria();

		$criteria->compare('week_id',$this->week_id);
		$criteria->compare('week_delivery_date',$this->week_delivery_date,true);
		$criteria->compare('week_notes',$this->week_notes,true);

		$criteria->condition = 'week_delivery_date > NOW()';
		$criteria->limit = 5;
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>false,
		));
	}
	
	/**
	 * Get the week id for the next delivery
	 */
	public function getCurrentWeekId()
	{
		$week=$this->findByAttributes('week_delivery_date > NOW()');
		return $week ? $week->id : false;
	}
	
	/**
	 * Get week object for the last week entered
	 */
	public static function getLastEnteredWeek()
	{
		$criteria=new CDbCriteria;
		$criteria->order='week_delivery_date DESC';
		$week=self::model()->find($criteria);
		return $week;
	}
}