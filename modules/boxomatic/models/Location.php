<?php

/**
 * This is the model class for table "locations".
 *
 * The followings are the available columns in table 'locations':
 * @property integer $location_id
 * @property string $location_name
 * @property string $location_delivery_value
 *
 * The followings are the available model relations:
 * @property Customers[] $customers
 */
class Location extends BoxomaticActiveRecord
{

    public function behaviors()
    {
        return array(
            'activerecord-relation' => array(
                'class' => 'boxomatic.extensions.active-relation-behavior.EActiveRecordRelationBehavior',
            )
        );
    }

    public $pickup_label = 'Pick Up';
    public $delivery_label = 'Delivery';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Location the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return $this->tablePrefix . 'locations';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('location_name', 'length', 'max' => 45),
            array('location_delivery_value', 'length', 'max' => 7),
            array('is_pickup', 'boolean'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('is_pickup, location_id, location_name, location_delivery_value', 'safe', 'on' => 'search'),
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
            'Users' => array(self::HAS_MANY, 'BoxomaticUser', 'location_id'),
            'DeliveryDates' => array(self::MANY_MANY, 'DeliveryDate', 'delivery_date_locations(location_id, delivery_date_id)')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'location_id' => 'Location',
            'location_name' => 'Location Name',
            'location_delivery_value' => 'Cost',
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

        $criteria = new CDbCriteria;

        $criteria->compare('location_id', $this->location_id);
        $criteria->compare('location_name', $this->location_name, true);
        $criteria->compare('location_delivery_value', $this->location_delivery_value, true);
        $criteria->compare('is_pickup', $this->is_pickup, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getLocation_and_delivery()
    {
        return $this->location_name . ' (' . SnapFormat::currency($this->location_delivery_value) . ')';
    }

    public static function getDeliveryList()
    {
        return CHtml::listData(self::model()->findAll('is_pickup=0'), 'location_id', 'location_and_delivery', 'delivery_label');
    }

    public static function getPickupList()
    {
        return CHtml::listData(self::model()->findAll('is_pickup=1'), 'location_id', 'location_and_delivery', 'pickup_label');
    }

    public static function getDeliveryAndPickupList()
    {
        return array_merge(self::getPickupList(), self::getDeliveryList());
    }
    
    public function getNextDeliveryDate()
    {
        $deadlineDays = SnapUtil::config('boxomatic/orderDeadlineDays');
        $c = new CDbCriteria;
        $c->with = 'Locations';
        $c->addCondition('date_sub(date, interval :deadlineDays day) > NOW()');
        $c->addCondition('Locations.location_id = :locationId');
        $c->params = array(
            ':deadlineDays' => $deadlineDays,
            ':locationId' => $this->location_id, 
        );
        return DeliveryDate::model()->find($c);
    }
    
    public function getFutureDeliveryDates($FromDate, $advance, $every=null, $intervalType='MONTH')
    {
        $deadlineDays = SnapUtil::config('boxomatic/orderDeadlineDays');
        $startingFrom = $FromDate->date;
        
        $dayOfWeek = date('N', strtotime($startingFrom)) + 1;
        
        $c = new CDbCriteria;
        $c->with = 'Locations';
        $c->addCondition('date >= :startingFrom');
        $c->addCondition('date <=  DATE_ADD(:startingFrom, interval :advance '.$intervalType.')');
        $c->addCondition('DAYOFWEEK(date) = :dayOfWeek');
        $c->addCondition('date_sub(date, interval :deadlineDays day) > NOW()');
        $c->addCondition('Locations.location_id = :locationId');
        $c->params = array(
            ':startingFrom' => $startingFrom,
            ':advance' => $advance,
            ':dayOfWeek' => $dayOfWeek,
            ':deadlineDays' => $deadlineDays,
            ':locationId' => $this->location_id,
        );
        
        $DDs = DeliveryDate::model()->findAll($c);
        
        //FB - This will break if more than 2 deliveries in one week..
        if($every == 'fortnight')
        {
            foreach (range(1, count($DDs), 2) as $key) {
                unset($DDs[$key]);
            }
            $DDs = array_merge($DDs); //reset the keys
        }
        
        return $DDs;
    }

}
