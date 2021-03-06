<?php

/**
 * This is the model class for table "boxes".
 *
 * The followings are the available columns in table 'boxes':
 * @property integer $box_id
 * @property integer $size_id
 * @property string $box_price
 * @property integer $delivery_date_id
 *
 * The followings are the available model relations:
 * @property DeliveryDates $date
 * @property BoxSizes $size
 */
class Box extends BoxomaticActiveRecord
{

    public $total;    //variable to store different kinds of aggregate totals
    //public $quantity; //variable to store the aggregate quantity of a box size (see: models/DeliveryDate.php, relation MergedBoxes)
    public $box_ids;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Box the static model class
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
        return $this->tablePrefix . 'boxes';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('size_id, delivery_date_id', 'numerical', 'integerOnly' => true),
            array('box_price', 'length', 'max' => 7),
            array('variation_name', 'length', 'max' => 100),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('box_id, size_id, box_price, delivery_date_id', 'safe', 'on' => 'search'),
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
            'DeliveryDate' => array(self::BELONGS_TO, 'DeliveryDate', 'delivery_date_id'),
            'BoxSize' => array(self::BELONGS_TO, 'BoxSize', 'size_id'),
            'BoxItems' => array(self::HAS_MANY, 'BoxItem', 'box_id'),
            'totalValue' => array(
                self::STAT, 'BoxItem', 'box_id', 'select' => 'SUM(item_value * item_quantity)'
            ),
            'UserBoxes' => array(self::HAS_MANY, 'UserBox', 'box_id'),
            'customerCount' => array(self::STAT, 'UserBox', 'box_id',
                'condition' => 'status=' . UserBox::STATUS_APPROVED . ' OR status=' . UserBox::STATUS_DELIVERED,
                'select' => 'SUM(quantity)'
            ),
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
            'delivery_date_id' => 'DeliveryDate',
            'variation_name' => 'Variation Name',
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

        $criteria->compare('box_id', $this->box_id);
        $criteria->compare('size_id', $this->size_id);
        $criteria->compare('box_price', $this->box_price, true);
        $criteria->compare('delivery_date_id', $this->delivery_date_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Duplicate a Box and all its items
     * @return boolean 
     */
    public function duplicate()
    {
        $newBox = new Box;
        $newBox->attributes = $this->attributes;
        $newBox->box_id = null;
        $newBox->save();

        foreach ($this->BoxItems as $BoxItem)
        {
            $newBoxItem = new BoxItem;
            $newBoxItem->attributes = $BoxItem->attributes;
            $newBoxItem->box_item_id = null;
            $newBoxItem->box_id = $newBox->box_id;
            $newBoxItem->save();
        }

        return true;
    }

    public function getRetailPrice()
    {
        $value = $this->totalValue;
        $markup = $this->BoxSize->box_size_markup / 100;
        return $value + ($value * $markup);
    }

    public function getName()
    {
        return (!empty($this->variation_name) ? ' <small>' . $this->variation_name . '</small><br />' : '') . $this->BoxSize->box_size_name;
    }

}
