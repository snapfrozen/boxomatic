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
			'MergedBoxes' => array(self::HAS_MANY, 'Box', 'week_id',
				'select'=>'*, MIN(MergedBoxes.box_id) as box_id, GROUP_CONCAT(MergedBoxes.box_id) as box_ids',
				'with'=>array('BoxSize'),
//				'condition'=>'status='.CustomerBox::STATUS_APPROVED,
				'order'=>'box_size_name DESC',
				'group'=>'MergedBoxes.week_id, MergedBoxes.size_id'
			),
			'Boxes' => array(self::HAS_MANY, 'Box', 'week_id',
				'with'=>'BoxSize',
				'order'=>'box_size_name DESC'
			),
			'BoxItemsContent' => array(self::HAS_MANY, 'BoxItem', array('box_id'=>'box_id'), 'through'=>'Boxes',
				'order'=>'Grower.grower_name ASC, BoxItemsContent.item_name',
				'select'=>'BoxItem.item_name, BoxItem.grower_id, BoxItem.item_value, BoxItem.item_unit, GROUP_CONCAT(box_item_id) as box_item_ids',
				'group'=>'BoxItemsContent.grower_id, item_name, item_value, item_unit',
				'with'=>'Grower',
			),
			'totalBoxValue'=> array(self::STAT, 'Box', 'week_id',
				'select'=>'SUM(item_value * item_quantity)',
				'condition'=>'size_id=:sizeId',
				'join'=>'JOIN box_items ON ' . $this->getTableAlias(). '.box_id=box_items.box_id'
            ),
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
	
	/**
	 * Get the deadline for this week
	 */
	public function getDeadline()
	{
		$deadlineDays=Yii::app()->params['orderDeadlineDays'];
		$deliveryDate=strtotime($this->week_delivery_date);
		return date('d-m-Y', strtotime('-' . $deadlineDays . ' days', $deliveryDate));
	}
}