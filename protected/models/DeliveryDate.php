<?php

/**
 * This is the model class for table "delivery_dates".
 *
 * The followings are the available columns in table 'delivery_dates':
 * @property integer $id
 * @property string $date
 * @property string $notes
 *
 * The followings are the available model relations:
 * @property Boxes[] $boxes
 */
class DeliveryDate extends CActiveRecord
{
	public function behaviors()
	{
		return array(
			'activerecord-relation'=>array(
				'class'=>'ext.active-relation-behavior.EActiveRecordRelationBehavior',
			)
		);
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return DeliveryDate the static model class
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
		return 'delivery_dates';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date, notes', 'length', 'max'=>45),
			array('disabled', 'boolean'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, disabled, date, notes', 'safe', 'on'=>'search'),
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
			'MergedBoxes' => array(self::HAS_MANY, 'Box', 'delivery_date_id',
				'select'=>'*, MIN(MergedBoxes.box_id) as box_id, GROUP_CONCAT(MergedBoxes.box_id) as box_ids',
				'with'=>array('BoxSize'),
//				'condition'=>'status='.CustomerBox::STATUS_APPROVED,
				'order'=>'box_size_name DESC',
				'group'=>'MergedBoxes.delivery_date_id, MergedBoxes.size_id'
			),
			'Boxes' => array(self::HAS_MANY, 'Box', 'delivery_date_id',
				'with'=>'BoxSize',
				'order'=>'box_size_name DESC'
			),
			'BoxItemsContent' => array(self::HAS_MANY, 'BoxItem', array('box_id'=>'box_id'), 'through'=>'Boxes',
				'order'=>'Supplier.name ASC, BoxItemsContent.item_name',
				'select'=>'BoxItem.item_name, BoxItem.supplier_id, BoxItem.supplier_product_id, BoxItem.item_value, BoxItem.item_unit, GROUP_CONCAT(box_item_id) as box_item_ids',
				'group'=>'BoxItemsContent.supplier_id, item_name, item_value, item_unit',
				'with'=>'Supplier',
			),
			'totalBoxValue'=> array(self::STAT, 'Box', 'delivery_date_id',
				'select'=>'SUM(item_value * item_quantity)',
				'condition'=>'size_id=:sizeId',
				'join'=>'JOIN box_items ON ' . $this->getTableAlias(). '.box_id=box_items.box_id'
            ),
			'Locations' => array(self::MANY_MANY, 'Location', 'delivery_date_locations(delivery_date_id,location_id)')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Delivery Date',
			'date' => 'Date Starting',
			'notes' => 'Notes',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('notes',$this->notes,true);
		$criteria->compare('disabled',$this->disabled,true);

		//$criteria->condition = 'date > NOW()';
		//$criteria->limit = 5;
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			//'pagination'=>false,
		));
	}
	
	/**
	 * Get the delivery date id for the next delivery
	 */
	public static function getCurrentDeliveryDateId()
	{
		$deadlineDays=Yii::app()->params['orderDeadlineDays'];
		$date=self::model()->find("date_sub(date, interval $deadlineDays day) > NOW()");
		return $date ? $date->id : false;
	}
	
	/**
	 * Get delivery date object for the last date entered
	 */
	public static function getLastEnteredDate()
	{
		$criteria=new CDbCriteria;
		$criteria->order='date DESC';
		$date=self::model()->find($criteria);
		return $date;
	}
	
	public function getFutureDeliveryDates()
	{
		$deadlineDays=Yii::app()->params['orderDeadlineDays'];
		$dayOfWeek=date('N',strtotime($this->date))+1;
		if($dayOfWeek == 8)
			$dayOfWeek = 1;
		
		$DeliveryDates=DeliveryDate::model()->findAll("date_sub(date, interval $deadlineDays day) > NOW() AND DAYOFWEEK(date) = '" . $dayOfWeek . "'");
		return CHtml::listData($DeliveryDates,'date','formatted_date');
	}
	
	/**
	 * Get the deadline for this date
	 */
	public function getDeadline()
	{
		$deadlineDays=Yii::app()->params['orderDeadlineDays'];
		$deliveryDate=strtotime($this->date);
		return date('d-m-Y', strtotime('-' . $deadlineDays . ' days', $deliveryDate));
	}
	
	public function getFormatted_date()
	{
		return Yii::app()->snapFormat->dayOfYear($this->date);
	}
}