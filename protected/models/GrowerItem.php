<?php

/**
 * This is the model class for table "grower_items".
 *
 * The followings are the available columns in table 'grower_items':
 * @property integer $item_id
 * @property integer $grower_id
 * @property string $item_name
 * @property string $item_value
 * @property string $item_unit
 * @property string $item_available_from
 * @property string $item_available_to
 *
 * The followings are the available model relations:
 * @property Growers $grower
 */
class GrowerItem extends CActiveRecord
{

    /*
     * grower name search attribute
     */
    public $grower_search;
    
    
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return GrowerItem the static model class
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
		return 'grower_items';
	}
	
	public function getUnitList()
	{
	    $unit_list = array(
			'KG' => 'Per kg',
	        'EA' => 'Each',
			'BUNCH' => 'Per bunch',
		);
	    return $unit_list;
	}
	
	public function getMonthList()
	{
	    $month_list = array();
	    for($i=1; $i <= 12; $i++)
	    {
	        $month_list[$i] = date('F', strtotime('2000-'.$i.'-05'));
	    }
	    return $month_list;
	}


	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('grower_id', 'numerical', 'integerOnly'=>true),
			array('item_name', 'length', 'max'=>45),
			array('item_value', 'length', 'max'=>7),
			array('item_unit', 'length', 'max'=>2),
			array('item_available_from, item_available_to', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('item_id, grower_id, grower_search, item_name, item_value, item_unit, item_available_from, item_available_to', 'safe', 'on'=>'search'),
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
			'Grower' => array(self::BELONGS_TO, 'Grower', 'grower_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'item_id' => 'Item',
			'grower_id' => 'Grower',
			'item_name' => 'Item Name',
			'item_value' => 'Item Value',
			'item_unit' => 'Item Unit',
			'item_available_from' => 'Item Available From',
			'item_available_to' => 'Item Available To',
			'grower_search' => 'Grower Name',
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

        $criteria->with = array( 'Grower' );
		$criteria->compare('item_id',$this->item_id);
		$criteria->compare('grower_id',$this->grower_id);
		$criteria->compare('Grower.grower_name', $this->grower_search, true );
		$criteria->compare('item_name',$this->item_name,true);
		$criteria->compare('item_value',$this->item_value,true);
		$criteria->compare('item_unit',$this->item_unit,true);
		$criteria->compare('item_available_from',$this->item_available_from,true);
		$criteria->compare('item_available_to',$this->item_available_to,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}