<?php

/**
 * This is the model class for table "customer_delivery_dates".
 *
 * The followings are the available columns in table 'customer_delivery_dates':
 * @property integer $id
 * @property integer $delivery_date_id
 * @property integer $user_id
 * @property integer $customer_location_id
 *
 * The followings are the available model relations:
 * @property DeliveryDates $date
 * @property Customers $customer
 * @property Locations $location
 */
class Order extends BoxomaticActiveRecord
{

    const STATUS_NOT_PROCESSED = 0;
    const STATUS_APPROVED = 1;
    const STATUS_DECLINED = 2;
    const STATUS_DELIVERED = 3;

    public $extras_item_names;
    //public $extras_total;
    public $search_full_name;
    public $customer_user_id;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Order the static model class
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
        return $this->tablePrefix . 'orders';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('delivery_date_id, user_id, location_id', 'required'),
            array('status, location_id, delivery_date_id, user_id', 'numerical', 'integerOnly' => true),
            array('customer_location_id', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('extras_item_names, search_full_name, status, id, delivery_date_id, user_id, customer_location_id', 'safe', 'on' => 'search'),
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
            'User' => array(self::BELONGS_TO, 'BoxomaticUser', 'user_id'),
            'UserLocation' => array(self::BELONGS_TO, 'UserLocation', 'customer_location_id'),
            'Location' => array(self::BELONGS_TO, 'Location', 'location_id'),
            'Extras' => array(self::HAS_MANY, 'OrderItem', 'order_id'),
            'UserBoxes' => array(self::HAS_MANY, 'UserBox', 'order_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'Customer DeliveryDate',
            'delivery_date_id' => 'DeliveryDate',
            'user_id' => 'Customer',
            'customer_location_id' => 'Customer Location',
            'status' => 'Status',
            'customer_user_id' => 'User ID',
            'search_full_name' => 'Customer',
            'extras_item_names' => 'Extras',
            'extras_total' => 'Total',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('delivery_date_id', $this->delivery_date_id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('status', $this->status);
        $criteria->compare('customer_location_id', $this->customer_location_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function extrasSearch($date)
    {
        $tablePrefix = SnapUtil::config('boxomatic/tablePrefix');
        $c = new CDbCriteria;
        $c->addCondition('t.delivery_date_id = :date');
        $c->select = 't.user_id, GROUP_CONCAT(oi.name SEPARATOR ", ") as extras_item_names, t.delivery_date_id, t.status, t.location_id, t.customer_location_id, t.id';
        $c->params = array(':date' => $date);
        //$c->with doesn't work very well with CActiveDataProvider
        $c->join = "INNER JOIN {$tablePrefix}order_items oi ON oi.order_id = t.id";
        $c->group = 't.user_id';

        $c->compare('CONCAT(u.first_name, u.last_name)', $this->search_full_name, true);
        $c->compare('status', $this->status);

        $c->compare('oi.name', $this->extras_item_names, true);
        if (!empty($this->search_full_name))
        {
            $c->join = 'INNER JOIN order_items oi ON oi.order_id = t.id ' .
                    'INNER JOIN users u ON u.id = t.user_id';
            $c->compare('CONCAT(u.first_name, u.last_name)', $this->search_full_name, true);
        }

        return new CActiveDataProvider($this, array('criteria' => $c));
    }

    /*
     * Only allow changes before the delivery deadline if the user is not an admin
     */

    public function beforeSave()
    {
        $DeliveryDate = $this->DeliveryDate;

        if (time() > strtotime($DeliveryDate->deadline) &&
                !Yii::app()->user->checkAccess('Admin') &&
                !isset(Yii::app()->user->shadow_id))
                //Yii::app()->getAuthManager()->checkAccess('Admin', Yii::app()->user->shadow_id))
            return false;
        else
            return true;
    }

    public function getLocation_key()
    {
        if ($this->customer_location_id)
            return $this->customer_location_id . '-' . $this->location_id;
        else
        {
            return $this->location_id;
        }
    }

    public function getDelivery_location()
    {
        return $this->Location->location_name;
    }

    public function getDelivery_address()
    {
        if ($this->UserLocation)
        {
            return $this->UserLocation->full_address;
        } else
        {
            return "";
        }
    }

    public function getDelivery_location_key()
    {
        if ($this->customer_location_id)
            return $this->customer_location_id . '-' . $this->location_id;
        else
        {
            return $this->location_id;
        }
    }

    /**
     * @return string the status text display for the current extra
     */
    public function getStatus_text()
    {
        $statusOptions = $this->statusOptions;
        return isset($statusOptions[$this->status]) ? $statusOptions[$this->status] : "unknown status ({$this->status})";
    }

    public function getStatusOptions()
    {
        return array(
            self::STATUS_NOT_PROCESSED => 'Not Processed',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_DECLINED => 'Declined',
            self::STATUS_DELIVERED => 'Collected/Delivered',
        );
    }

    public function setDelivered()
    {
        if ($this->status != self::STATUS_DELIVERED)
        {
            $this->status = self::STATUS_DELIVERED;
            return $this->save();
        }
        return false;
    }

    public function getExtras_total()
    {
        $criteria = new CDbCriteria;
        $criteria->select = 'SUM(price * quantity) as date_total';
        $criteria->with = 'Order';
        $criteria->condition = 'delivery_date_id=:dateId AND user_id=:customerId';
        $criteria->params = array(':dateId' => $this->delivery_date_id, ':customerId' => $this->user_id);
        $extras = OrderItem::model()->find($criteria);
        $extrasTotal = $extras ? $extras->date_total : 0;

        return $extrasTotal;
    }
    
    public function getBox_total()
    {
        $total = 0;
        foreach($this->UserBoxes as $UB) {
            $total += $UB->total_price;
        }
        return $total;
    }
    
    public function getTotal()
    {
        return $this->extras_total + $this->box_total;
    }
    
    public function clearOrder()
    {
        foreach($this->Extras as $OI) {
            $OI->delete();
        }
        foreach($this->UserBoxes as $UB) {
            $UB->delete();
        }
        return true;
    }
    
    public static function getPastOrders($userId)
    {
        return self::model()->with('DeliveryDate')->findAll(array(
            'condition' => 'user_id = :userId && DeliveryDate.date < NOW()',
            'order' => 'DeliveryDate.date DESC',
            'params' => array (
                ':userId' => $userId,
            )
        ));
    }

}
