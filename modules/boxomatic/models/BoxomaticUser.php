<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property integer $user_id
 * @property string $email
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string $user_phone
 * @property string $user_mobile
 * @property string $user_address
 * @property string $user_address2
 * @property string $user_suburb
 * @property string $user_state
 * @property string $user_postcode
 * @property string $last_login_time
 */
class BoxomaticUser extends User
{

    public $password_repeat;
    public $password_current;
    public $verify_code;
    public $total_boxes;
    public $searchAdmin = false;
    public $search_customer_notes;
    public $tag_name_search;
    public $full_name_search;
    public $dont_want_search;
    public $total_payments;
    public $order_items;
    public $ordered_boxes;

    public function behaviors()
    {
        return array(
            'activerecord-relation' => array(
                'class' => 'boxomatic.extensions.active-relation-behavior.EActiveRecordRelationBehavior',
            )
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * @return User the static model class
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
        return '{{users}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('password, first_name, last_name', 'required'),
            array('email', 'required', 'on' => 'register'),
            array('location_id, user_location_id, id', 'numerical', 'integerOnly' => true),
            array('email, password', 'length', 'max' => 255),
            array('email', 'unique'),
            array('email', 'email'),
            array('password', 'compare', 'on' => 'changePassword, register'),
            array('verify_code, password_repeat', 'safe'),
            array('first_name, last_name, user_phone, user_mobile, user_suburb, user_state, user_postcode', 'length', 'max' => 45),
            array('user_address, user_address2, email', 'length', 'max' => 150),
            array('tag_names', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('full_name_search, dont_want_search, tag_name_search, search_customer_notes, id, first_name, last_name, full_name, user_phone, user_mobile, user_address, user_address2, email, user_suburb, user_state, user_postcode, last_login_time', 'safe', 'on' => 'search'),
            // verifyCode needs to be entered correctly
            array('verify_code', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements(), 'on' => 'register'),
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
            'Supplier' => array(self::HAS_ONE, 'Supplier', 'user_id'),
            'DontWant' => array(self::MANY_MANY, 'SupplierProduct', SnapUtil::config('boxomatic/tablePrefix') . 'user_dontwant_products(user_id,supplier_product_id)'),
            'Tags' => array(self::MANY_MANY, 'Tag', SnapUtil::config('boxomatic/tablePrefix') . 'user_tags(user_id,tag_id)'),
            'UserLocations' => array(self::HAS_MANY, 'UserLocation', 'user_id'),
            'UserLocation' => array(self::BELONGS_TO, 'UserLocation', 'user_location_id'),
            'Location' => array(self::BELONGS_TO, 'Location', 'location_id'),
            'Payments' => array(self::HAS_MANY, 'UserPayment', 'user_id'),
            'Orders' => array(self::HAS_MANY, 'Order', 'user_id'),
                /*
                  'totalPayments'=>array(
                  self::STAT, 'UserPayment', 'user_id', 'select' => 'SUM(payment_value)'
                  ),
                 */
        );
    }
    
    public function getFutureOrders($days=28) 
    {
        $startingFrom = date('Y-m-d');
        
        $c = new CDbCriteria;
        $c->addCondition('user_id = :userId');
        $c->addCondition('date >= :startingFrom');
        $c->addCondition('date <= DATE_ADD(:startingFrom, interval :days DAY)');
        $c->with = 'DeliveryDate';
        $c->params = array(
            ':userId' => $this->id,
            ':startingFrom' => $startingFrom,
            ':days' => $days,
        );
        return Order::model()->findAll($c);
    }

    public function getTotalPayments()
    {
        $c = new CDbCriteria;
        $c->with = array('Payments');
        $c->select = 'SUM(payment_value) as total_payments';
        $c->addCondition('t.id=:userId');
        $c->params = array(
            ':userId' => $this->id,
        );
        $model = self::model()->find($c);
        return $model ? $model->total_payments : 0;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'user_id' => 'Customer',
            'password' => 'Password',
            'password_repeat' => 'Repeat Password',
            'password_current' => 'Current Password',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'full_name' => 'Full Name',
            'user_phone' => 'Phone',
            'user_mobile' => 'Mobile',
            'user_address' => 'Street Address',
            'user_address2' => '&nbsp;',
            'email' => 'Email',
            'user_suburb' => 'Suburb',
            'user_state' => 'State',
            'user_postcode' => 'Postcode',
            'last_login_time' => 'Last Login Time',
            'search_customer_notes' => 'Customer Notes',
            'delivery_location_key' => 'Default Delivery Location',
            'tag_name_search' => 'Tags',
            'location_id' => 'Location',
            'user_location_id' => 'Address',
            'dont_want_search' => 'Doesn\'t Want',
            'order_items' => 'Items',
            'full_name_search' => 'Full Name',
            'ordered_boxes' => 'Ordered Boxes',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search($DeliveryDate = null)
    {
        $tablePrefix = SnapUtil::config('boxomatic/tablePrefix');

        $pageSize = isset($_GET['pageSize']) ? $_GET['pageSize'] : 10;
        Yii::app()->user->setState('pageSize', $pageSize);


        $criteria = new CDbCriteria;
        $criteria->select = 't.*';
        $criteria->with = array();
        $criteria->join = '';

        if (!empty($this->tag_name_search))
        {
            $criteria->with [] = 'Tags';
            $criteria->together = true;
            $criteria->compare('Tags.id', $this->tag_name_search);
        }

        if (!empty($this->dont_want_search))
        {
            $criteria->join .=
                    "INNER JOIN {$tablePrefix}user_dontwant_products udw ON udw.user_id = t.id "
                    . "INNER JOIN {$tablePrefix}supplier_products sp ON sp.id = udw.supplier_product_id ";
            $criteria->compare('sp.name', $this->dont_want_search, true);
            $criteria->group = 't.id';
        }

        if ($DeliveryDate)
        {
            $c = new CDbCriteria;
            $c->select = 't.user_id';
            $c->distinct = true;
            $c->addCondition('Box.delivery_date_id = :ddId');
            $c->addCondition('t.status = ' . UserBox::STATUS_APPROVED . ' OR t.status = ' . UserBox::STATUS_DELIVERED);
            $c->with = 'Box';
            $c->params = array(
                ':ddId' => $DeliveryDate->id,
            );
            $userIds = array();
            $results = UserBox::model()->findAll($c);
            foreach ($results as $r)
                $userIds[] = $r->user_id;

            $criteria->select = 't.*, GROUP_CONCAT(DISTINCT OrderItems.name SEPARATOR ", ") as order_items, GROUP_CONCAT(DISTINCT BoxSizes.box_size_name SEPARATOR ", ") as ordered_boxes';
            $criteria->distinct = true;
            $criteria->addInCondition('t.id', $userIds);
            $criteria->join .=
                    "INNER JOIN {$tablePrefix}orders Orders ON Orders.user_id = t.id "
                    . "INNER JOIN {$tablePrefix}order_items OrderItems ON OrderItems.order_id = Orders.id "
                    . "INNER JOIN {$tablePrefix}user_boxes UserBoxes ON UserBoxes.order_id = Orders.id "
                    . "INNER JOIN {$tablePrefix}boxes Boxes ON UserBoxes.box_id = Boxes.box_id "
                    . "INNER JOIN {$tablePrefix}box_sizes BoxSizes ON Boxes.size_id = BoxSizes.id ";

            $criteria->group = 't.id, UserBoxes.order_id';

            $criteria->addCondition('Orders.delivery_date_id = :ddId');
            //$criteria->addCondition('Boxes.delivery_date_id = :ddId');
            $criteria->params[':ddId'] = $DeliveryDate->id;
        }

        $criteria->compare('CONCAT(first_name,last_name)', $this->full_name_search, true);
        $criteria->compare('notes', $this->search_customer_notes, true);
        $criteria->compare('id', $this->id);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('first_name', $this->first_name, true);
        $criteria->compare('last_name', $this->last_name, true);
        $criteria->compare('user_phone', $this->user_phone, true);
        $criteria->compare('user_mobile', $this->user_mobile, true);
        $criteria->compare('user_address', $this->user_address, true);
        $criteria->compare('user_address2', $this->user_address2, true);
        $criteria->compare('user_suburb', $this->user_suburb, true);
        $criteria->compare('user_state', $this->user_state, true);
        $criteria->compare('user_postcode', $this->user_postcode, true);
        $criteria->compare('last_login_time', $this->last_login_time, true);
        $criteria->compare('updated', $this->updated, true);
        //$criteria->compare('updated_by',$this->updated_by);
        $criteria->compare('created', $this->created, true);
        //$criteria->compare('created_by',$this->created_by);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => $pageSize,
            ),
        ));
    }

    /**
     * Encrypt password before saving
     */
    public function afterValidate()
    {
        if ($this->scenario == 'changePassword' || $this->scenario == 'register')
        {
            $this->password = SnapUtil::doHash($this->password);
            $this->password_repeat = SnapUtil::doHash($this->password_repeat);
        }
        return parent::beforeSave();
    }

    public function afterSave()
    {
        parent::afterSave();

        //Register to MailChimp on signup
        Yii::import('application.external.*');
        if ($this->scenario == 'register' && isset($_POST['subscribe']))
        {
            $MailChimp = new MailChimp(SnapUtil::config('boxomatic/mailChimpApiKey'));
            $result = $MailChimp->call('lists/subscribe', array(
                'id' => SnapUtil::config('boxomatic/mailChimpListId'),
                'email' => array('email' => $this->email),
                'merge_vars' => array(
                    'MERGE2' => $this->full_name // MERGE name from list settings
                // there MERGE fields must be set if required in list settings
                ),
                'double_optin' => false,
                'update_existing' => true,
                'replace_interests' => false
            ));

            if ($result === false)
            {
                // response wasn't even json
            } else if (isset($result->status) && $result->status == 'error')
            {
                // Error info: $result->status, $result->code, $result->name, $result->error
                Yii::log('Error subscribing user ' . $this->id . ': ' . print_r($result, true), 'error', 'system.web.CActiveRecord');
            }
        }
    }

    /**
     * Foodbox only allows assignment of a single role
     * @return boolean whether the role was set or not
     */
    public function setRole($role)
    {
        if (!Yii::app()->authManager->isAssigned($role, $this->id))
        {
            Yii::app()->authManager->revoke($this->getRole(), $this->id);
            Yii::app()->authManager->assign($role, $this->id);
            return true;
        }
        return false;
    }

    /**
     * Foodbox only allows assignment of a single role
     * @return boolean 
     */
    public function getRole()
    {
        $roles = Yii::app()->authManager->getRoles($this->id);
        if (!empty($roles))
        {
            $keys = array_keys($roles);
            return array_pop($keys);
        } else
        {
            return false;
        }
    }

    public function getFull_address()
    {
        $addr = array();
        if (!empty($this->user_address))
            $addr[] = $this->user_address;
        if (!empty($this->user_address2))
            $addr[] = $this->user_address2;
        if (!empty($this->user_suburb))
            $addr[] = $this->user_suburb;
        if (!empty($this->user_state))
            $addr[] = $this->user_state;
        if (!empty($this->user_postcode))
            $addr[] = $this->user_postcode;

        return implode(', ', $addr);
    }

    public function getFull_name()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getBfb_id()
    {
        return 'FG' . $this->id;
    }

    public function getFull_name_and_balance()
    {
        return $this->id . ': ' . $this->full_name . ' (' . SnapFormat::currency($this->balance) . ')';
    }

    /*
      public function extrasSearch($date)
      {

      $c = new CDbCriteria;
      $c->addCondition('cdd.delivery_date_id = :date');
      $c->select = 't.user_id, GROUP_CONCAT(cddi.name SEPARATOR ", ") as extras_item_names';
      $c->params = array(':date'=>$date);
      //$c->with doesn't work very well with CActiveDataProvider
      $c->join =
      'INNER JOIN customer_delivery_dates cdd ON cdd.user_id = t.user_id '.
      'INNER JOIN customer_delivery_date_items cddi ON cddi.order_id = cdd.id';
      $c->group = 't.user_id';

      $c->compare('cddi.name',$this->extras_item_names,true);
      if(!empty($this->search_full_name)) {
      $c->join =
      'INNER JOIN customer_delivery_dates cdd ON cdd.user_id = t.user_id '.
      'INNER JOIN customer_delivery_date_items cddi ON cddi.order_id = cdd.id '.
      'INNER JOIN users u ON u.user_id = t.user_id';
      $c->compare('CONCAT(u.first_name, u.last_name)',$this->search_full_name,true);
      }

      return new CActiveDataProvider($this,array('criteria'=>$c));
      }
     */

    public function getBalance()
    {
        return $this->totalPayments;
    }

    public function totalByDeliveryDate($dateId)
    {
        $customerId = $this->id;

        $criteria = new CDbCriteria;
        $criteria->condition = 'delivery_date_id=:dateId AND user_id=:customerId';
        $criteria->params = array(':dateId' => $dateId, ':customerId' => $customerId);
        $criteria->select = 'SUM((box_price * quantity) + (delivery_cost * quantity)) as date_total';

        $boxes = UserBox::model()->with('Box')->find($criteria);
        $boxTotal = $boxes ? $boxes->date_total : 0;

        $criteria->with = 'Order';
        $criteria->select = 'SUM(price * quantity) as date_total';
        $extras = OrderItem::model()->find($criteria);

        $extrasTotal = $extras ? $extras->date_total : 0;

        return $boxTotal + $extrasTotal;
    }

    public function extrasTotalByDeliveryDate($dateId)
    {
        $customerId = $this->user_id;

        $criteria = new CDbCriteria;
        $criteria->select = 'SUM(price * quantity) as date_total';
        $criteria->with = 'Order';
        $criteria->condition = 'delivery_date_id=:dateId AND user_id=:customerId';
        $criteria->params = array(':dateId' => $dateId, ':customerId' => $customerId);
        $extras = OrderItem::model()->find($criteria);
        $extrasTotal = $extras ? $extras->date_total : 0;

        return $extrasTotal;
    }

    public function totalDeliveryByDeliveryDate($dateId)
    {
        $customerId = Yii::app()->user->user_id;

        $criteria = new CDbCriteria;
        $criteria->condition = 'delivery_date_id=:dateId AND user_id=:customerId';
        $criteria->params = array(':dateId' => $dateId, ':customerId' => $customerId);
        $criteria->select = 'SUM(delivery_cost * quantity) as date_total';

        $result = UserBox::model()->with('Box')->find($criteria);
        return $result ? $result->date_total : '';
    }

    public function totalBoxesByDeliveryDate($dateId)
    {
        $customerId = Yii::app()->user->user_id;

        $criteria = new CDbCriteria;
        $criteria->condition = 'delivery_date_id=:dateId AND user_id=:customerId';
        $criteria->params = array(':dateId' => $dateId, ':customerId' => $customerId);
        $criteria->select = 'SUM((box_price * quantity)) as date_total';

        $result = UserBox::model()->with('Box')->find($criteria);
        return $result ? $result->date_total : '';
    }

    public function getFulfilled_order_total()
    {
        $deadlineDays = SnapUtil::config('boxomatic/orderDeadlineDays');

        $customerId = Yii::app()->user->user_id;
        $deliveryDateDeadline = date('Y-m-d', strtotime('+' . $deadlineDays . ' days'));

        $criteria = new CDbCriteria;
        $criteria->with = array('Box.DeliveryDate');
        $criteria->condition = 'date<=:deliveryDateDeadline AND user_id=:customerId';
        $criteria->params = array(':deliveryDateDeadline' => $deliveryDateDeadline, ':customerId' => $customerId);
        $criteria->select = 'SUM((box_price * quantity) + (delivery_cost * quantity)) as fulfilled_total';

        $result = UserBox::model()->with('Box')->find($criteria);
        return $result ? $result->fulfilled_total : 0;
    }

    public function getDeliveryLocations()
    {
        //$this->DeliveryLocations;
        $pickupLocations = Location::model()->getPickupList();
        $custLocations = CHtml::listData($this->UserLocations, 'location_key', 'full_location', 'delivery_label');
        return array_merge($custLocations, $pickupLocations);
    }

    public function getDelivery_location_key()
    {
        if ($this->user_location_id)
            return $this->user_location_id . '-' . $this->location_id;
        else
        {
            return $this->location_id;
        }
    }

    public function updateOrderDeliveryLocations()
    {
        //Make sure we have a fresh Customer object with now CDbExpressions set for attributes
        $Customer = self::model()->findByPk($this->user_id);

        $deadlineDays = SnapUtil::config('boxomatic/orderDeadlineDays');
        $CustDeliveryDates = Order::model()->with('DeliveryDate')->findAllByAttributes(array(
            'user_id' => $this->id,
                ), "date_sub(DeliveryDate.date, interval $deadlineDays day) > NOW()");

        foreach ($CustDeliveryDates as $CustDate)
        {
            $CustDate->location_id = $this->location_id;
            if (empty($this->user_location_id))
            {
                $CustDate->user_location_id = new CDbExpression('NULL');
            } else
                $CustDate->user_location_id = $CustDate->user_location_id;

            $CustDate->save();
        }
    }

    public function getDelivery_location()
    {
        if ($this->UserLocation)
        {
            return $this->Location->location_name . ': ' . $this->UserLocation->full_address;
        } else
        {
            return $this->Location->location_name;
        }
    }

    /**
     * Find all customers that have no future orders 
     */
    public function findAllWithNoOrders()
    {
        $NextDelivery = DeliveryDate::model()->find(array(
            'condition' => 'date > NOW()',
            'order' => 'date ASC',
        ));

        $criteria = new CDbCriteria();
        $criteria->with = array(
            'User' => array(
                'joinType' => 'INNER JOIN'
            ),
            'UserBoxes' => array(
                'with' => array(
                    'Box' => array(
                        'with' => array(
                            'DeliveryDate' => array()
                        )
                    )
                ),
            ),
        );
        $criteria->order = 'first_name ASC';
        $criteria->select = '*, COUNT(UserBoxes.user_box_id) AS total_orders, MAX(DeliveryDate.date) as last_order';
        $criteria->group = 't.user_id';
        $criteria->having = 'last_order="' . $NextDelivery->date . '"';
        //$criteria->addCondition('DeliveryDate.date < DATE_ADD(NOW(), INTERVAL 7 DAY)');
        //$criteria->addCondition('DeliveryDate.date > NOW()');

        return $this->findAll($criteria);
    }

    /**
     * random password generation method
     *
     * @return string generated password
     * @param string length of password
     * @param int strength of password
     */
    public function generatePassword($length = 9, $strength = 0)
    {
        $vowels = 'aeuy';
        $consonants = 'bdghjmnpqrstvz';
        if ($strength & 1)
        {
            $consonants .= 'BDGHJLMNPQRSTVWXZ';
        }
        if ($strength & 2)
        {
            $vowels .= "AEUY";
        }
        if ($strength & 4)
        {
            $consonants .= '23456789';
        }
        if ($strength & 8)
        {
            $consonants .= '@#$%';
        }

        $password = '';
        $alt = time() % 2;
        for ($i = 0; $i < $length; $i++)
        {
            if ($alt == 1)
            {
                $password .= $consonants[(rand() % strlen($consonants))];
                $alt = 0;
            } else
            {
                $password .= $vowels[(rand() % strlen($vowels))];
                $alt = 1;
            }
        }
        return $password;
    }

    public function resetPasswordAndSendWelcomeEmail()
    {
        $this->scenario = 'changePassword';
        $newPassword = $this->generatePassword(8, 3);

        $this->password = SnapFormt::doHash($newPassword);
        $this->save(false);

        $message = new YiiMailMessage('Welcome to ' . Yii::app()->name);
        $message->view = 'welcome';
        $message->setBody(array('User' => $this, 'newPassword' => $newPassword), 'text/html');
        $email = trim($this->email);

        $validator = new CEmailValidator();
        if ($validator->validateValue($email))
        {
            $adminEmail = SnapUtil::config('boxomatic/adminEmail');
            $adminEmailFromName = SnapUtil::config('boxomatic/adminEmailFromName');
            $message->setFrom(array($adminEmail => $adminEmailFromName));
            $message->addTo($email);
            if (!@Yii::app()->mail->send($message))
            {
                return false;
            }
        } else
        {
            return false;
        }

        return true;
    }

    public function getDont_want_items()
    {
        $items = CHtml::listData($this->DontWant, 'id', 'name');
        return implode($items, ', ');
    }

    public function getTag_names()
    {
        $tags = CHtml::listData($this->Tags, 'id', 'name');
        return implode($tags, ', ');
    }

    public function setTag_names($data)
    {
        $tagNames = !empty($data) ? explode(',', $data) : array();
        $criteria = new CDbCriteria();
        $criteria->addInCondition("name", $tagNames);

        $tags = Tag::model()->findAll($criteria);

        $currentTags = CHtml::listData($tags, 'id', 'name');
        $newTags = array_diff($tagNames, $currentTags);

        $newTagIds = array();
        foreach ($newTags as $name)
        {
            $Tag = new Tag();
            $Tag->name = $name;
            $Tag->save();
            $newTagIds[] = $Tag->id;
        }

        $allTagIds = array_merge(array_keys($currentTags), $newTagIds);

        $this->Tags = $allTagIds;
        $this->save();
        Tag::deleteUnusedTags();
    }

}
