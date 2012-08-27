<?php

/**
 * This is the model class for table "box_items".
 *
 * The followings are the available columns in table 'box_items':
 * @property integer $box_item_id
 * @property string $item_name
 * @property integer $box_id
 * @property string $item_value
 * @property integer $grower_id
 */
class BoxItem extends CActiveRecord
{
	public $total;
	public $box_item_ids;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BoxItem the static model class
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
		return 'box_items';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
//			array('box_item_id', 'required'),
			array('box_item_id, box_id, grower_id', 'numerical', 'integerOnly'=>true),
			array('item_quantity', 'numerical'),
			array('item_name, item_value', 'length', 'max'=>45),
			array('item_unit', 'length', 'max'=>5),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('box_item_id, item_name, box_id, item_value, grower_id', 'safe', 'on'=>'search'),
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
			'Box' => array(self::BELONGS_TO, 'Box', 'box_id'),
			'Grower' => array(self::BELONGS_TO, 'Grower', 'grower_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'box_item_id' => 'Box Item',
			'item_name' => 'Item Name',
			'box_id' => 'Box',
			'item_value' => 'Item Value',
			'grower_id' => 'Grower',
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

		$criteria->compare('box_item_id',$this->box_item_id);
		$criteria->compare('item_name',$this->item_name,true);
		$criteria->compare('box_id',$this->box_id);
		$criteria->compare('item_value',$this->item_value,true);
		$criteria->compare('grower_id',$this->grower_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Returns the human readable unit label for the BoxItem
	 */
	public function getItem_unit_label()
	{
		return Yii::app()->params['itemUnits'][$this->item_unit];
	}
	
	/**
	 * 
	 */
	static function itemTotal($itemIds)
	{
		$Item = self::model()->find(array(
			'select'=>'SUM(item_value * quantity) as total',
			'condition'=>'box_item_id IN (' . $itemIds . ')',
		));
		
		return $Item->total;
	}
	
	/**
	 * Get the total quantity of items for all customers for the given BoxItem ids
	 */
	static function totalQuantity($itemIds)
	{
		$Item = self::model()->with(array('Box'=>array('with'=>'CustomerBoxes')))->find(array(
			'select'=>'SUM(item_quantity * quantity) as total',
			'condition'=>'box_item_id IN (' . $itemIds . ')',
		));
		
		return $Item->total;
	}
	
	/**
	 * Get the wholesale cost for a given week_id
	 */
	static function weekWholesale($weekId)
	{
		$Item = self::model()->with(array('Box'=>array('with'=>'CustomerBoxes')))->find(array(
			'select'=>'SUM(quantity * item_value) as total',
			'condition'=>'week_id = ' . $weekId . '',
		));
		
		return $Item->total;
	}
	
	/**
	 * Get the wholesale cost for a given week_id
	 */
	static function weekRetail($weekId)
	{
		$Item = self::model()->with(array('Box'=>array('with'=>array('CustomerBoxes','BoxSize') )))->find(array(
			'select'=>'SUM(quantity * item_value * (box_size_markup/100) ) + SUM(quantity * item_value) as total',
			'condition'=>'week_id = ' . $weekId . '',
		));
		
		return $Item->total;
	}
	
	/**
	 * Get the week target
	 */
	static function weekTarget($weekId)
	{
		$Item = self::model()->with(array('Box'=>array('with'=>array('CustomerBoxes') )))->find(array(
			'select'=>'SUM(quantity * box_price) as total',
			'condition'=>'week_id = ' . $weekId . '',
		));
		
		return $Item->total;
	}
}