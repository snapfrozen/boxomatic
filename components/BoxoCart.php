<?php

class BoxoCart extends CComponent
{
    public $delivery_date_id;
    public $delivery_day = null;
    public $Customer = null;
    
    protected $_location_id;
    protected $_user;
    protected $_SupplierProduct = array();
    protected $_UserBox = array();
    protected $_SupplierProduct_Before = array();   //Cart status before changes were made
    protected $_UserBox_Before = array();           //Cart status before changes were made
    
    //protected $_Orders;
    
    public function __construct()
    {
        $this->_user = Yii::app()->user;
        $User = isset($this->_user->id) ? BoxomaticUser::model()->findByPk($this->_user->id) : false;
        $this->Customer = $User;
        if($User) {
            $this->delivery_day = $User->delivery_day;
        }
        
        $this->delivery_date_id = $this->_user->getState('boxocart.delivery_date_id', null);
        $this->_location_id = $this->_user->getState('boxocart.location_id', $User ? $User->location_id : null);
        $this->_SupplierProduct = $this->_user->getState('boxocart.SupplierProduct', $this->_SupplierProduct, array());
        $this->_UserBox = $this->_user->getState('boxocart.UserBox', $this->_UserBox, array());
        $this->_SupplierProduct_Before = $this->_user->getState('boxocart.SupplierProduct_Before', $this->_SupplierProduct_Before, array());
        $this->_UserBox_Before = $this->_user->getState('boxocart.UserBox_Before', $this->_UserBox_Before, array());
    }
    
    public function setLocation_id($id)
    {
        $this->_user->setState('boxocart.location_id', $id);
        $this->_location_id = $id;
    }
    
    public function getLocation_id()
    {
        return $this->_location_id;
    }
    
    public function setDelivery_date_id($id)
    {
        $this->_user->setState('boxocart.delivery_date_id', $id);
        $this->delivery_date_id = $id;
    }
    
    public function addItems($data)
    {
        $itemsAdded = false;
        foreach($data as $modelName => $orders)
        {
            foreach($orders as $id => $qty) {
                $this->addItem($modelName, $id, $qty);
            }
            $itemsAdded = true;
        }
        return $itemsAdded;
    }
    
    public function updateItems($data)
    {
        $itemsAdded = false;
        foreach($data as $modelName => $orders)
        {
            foreach($orders as $id => $qty) {
                $this->updateItem($modelName, $id, $qty);
            }
            $itemsAdded = true;
        }
        return $itemsAdded;
    }
    
    public function updateItem($modelName, $id, $qty)
    {
        $fieldName = '_'.$modelName;
        
        //Remove if the new quantity is 0
        if($qty <= 0) {
            unset($this->{$fieldName}[$this->delivery_date_id][$id]);
        } else if(isset($this->{$fieldName}[$this->delivery_date_id][$id])) {
            $this->{$fieldName}[$this->delivery_date_id][$id] = $qty;
        }
        $this->_user->setState('boxocart.'.$modelName, $this->$fieldName);
        return $this;
    }
    
    public function addItem($modelName, $id, $qty)
    {
        $fieldName = '_'.$modelName;
        if(isset($this->{$fieldName}[$this->delivery_date_id][$id])) {
            $this->{$fieldName}[$this->delivery_date_id][$id] += $qty;
        } else {
            $this->{$fieldName}[$this->delivery_date_id][$id] = $qty;
        }
        
        //Remove if the new quantity is 0
        if($this->{$fieldName}[$this->delivery_date_id][$id] <= 0) {
            unset($this->{$fieldName}[$this->delivery_date_id][$id]);
        }
        
        $this->_user->setState('boxocart.'.$modelName, $this->$fieldName);
        return $this;
    }
    
    public function getProducts($which='after')
    {
        $order = array();

        $products = array();
        if(($which=='after' || $which=='both') && isset($this->_SupplierProduct[$this->delivery_date_id])) 
        {
            $products = $this->_SupplierProduct[$this->delivery_date_id];
        } 
        else if($which=='before' && isset($this->_SupplierProduct_Before[$this->delivery_date_id])) 
        {
            $products = $this->_SupplierProduct_Before[$this->delivery_date_id];
        }  
        
        if ($which=='both' && isset($this->_SupplierProduct_Before[$this->delivery_date_id])) 
        {    
            foreach($this->_SupplierProduct_Before[$this->delivery_date_id] as $pid => $qty)
            {
                if(!isset($products[$pid])) {
                    $products[$pid] = $qty;
                }
            }
        }
        
        if(empty($products)) {
            return $order;
        }
        
        $Order = $this->_getOrder($this->_user->id, $this->delivery_date_id, $this->_location_id);
        $Order = $Order->id ? $Order : false; //We don't to create a new order
        foreach($products as $id => $qty) 
        {
            $OI = null;
            if($Order)
            {
                $OI = OrderItem::model()->findByAttributes(array(
                    'supplier_product_id' => $id,
                    'order_id' => $Order->id,
                ));
            }
            if(!$OI) {
                $OI = new OrderItem;
                $OI->supplier_product_id = $id;
            }
            $SP = $OI->SupplierProduct;
            $OI->quantity = $qty;
            //Only update price etc if it hasn't been set yet.
            if(empty($OI->name))
            {
                $OI->price = $SP->item_sales_price;
                $OI->name = $SP->name;
                $OI->unit = $SP->unit;
                $OI->packing_station_id = $SP->packing_station_id;
            }
            $order[] = $OI;
        }
        return $order;
    }
    
    public function getUserBoxes($which='after')
    {
        $order = array();

        $boxes = array();
        if(($which=='after' || $which=='both') && isset($this->_UserBox[$this->delivery_date_id])) 
        {
            $boxes = $this->_UserBox[$this->delivery_date_id];
        } 
        else if($which=='before' && isset($this->_UserBox_Before[$this->delivery_date_id])) 
        {
            $boxes = $this->_UserBox_Before[$this->delivery_date_id];
        }  
        
        if ($which=='both' && isset($this->_UserBox_Before[$this->delivery_date_id])) 
        {    
            foreach($this->_UserBox_Before[$this->delivery_date_id] as $pid => $qty)
            {
                if(!isset($boxes[$pid])) {
                    $boxes[$pid] = $qty;
                }
            }
        }
        
        if(empty($boxes)) {
            return $order;
        }
        
        foreach($boxes as $id => $qty) 
        {
            $UB = new UserBox;
            $UB->quantity = $qty;
            $UB->box_id = $id;
            $UB->price = $UB->Box->box_price;
            $UB->user_id = $this->_user->id;
            //$UB->location_id = $this->_location_id;
            $order[] = $UB;
        }
        return $order;
    }
    
    public function getTotal($dateId=null, $which='after')
    {
        $curDate = $this->delivery_date_id;
        if($dateId) {
            $this->delivery_date_id = $dateId;
        }
        
        $total = 0;
        foreach($this->getProducts($which) as $OI) {
            $total += $OI->total;
        }
        foreach($this->getUserBoxes($which) as $UB) {
            $total += $UB->total_price;
        }
        
        $this->delivery_date_id = $curDate;
        return $total;
    }
    
    public function getLocation()
    {
        return Location::model()->findByPk($this->_location_id);
    }
    
    public function getDeliveryDate()
    {
        return DeliveryDate::model()->findByPk($this->delivery_date_id);
    }
    
    /**
     * @todo: currently everything is stored by date_id but this breaks down if 
     * someone wants to order for two different locations on the same day.
     */
    public function getOrders()
    {
        $Orders = array();
        foreach($this->Customer->getFutureOrders() as $Order) 
        {
            
        }
    }
        
    public function getDeliveryDates($combined=false)
    {
        $ddIds = array_keys($this->_SupplierProduct);
        $ddIds = array_merge($ddIds, array_keys($this->_UserBox));
        if($combined) {
            $ddIds = array_merge($ddIds, array_keys($this->_SupplierProduct_Before));
            $ddIds = array_merge($ddIds, array_keys($this->_UserBox_Before));
        }
        sort($ddIds);
        
        $DDs = array();
        foreach($ddIds as $id) {
            $DDs[$id] = DeliveryDate::model()->findByPk($id);
        }
        return $DDs;
    }
    
    
    /**
     * @param type $DDs Array of DeliveryDate objects
     */
    public function repeatCurrentOrder($DDs)
    {
        $SPs = isset($this->_SupplierProduct[$this->delivery_date_id]) ? $this->_SupplierProduct[$this->delivery_date_id] : array();
        $UBs = isset($this->_UserBox[$this->delivery_date_id]) ? $this->_UserBox[$this->delivery_date_id] : array();
        
        $allOk = true;
        foreach($DDs as $DD)
        {
            //Clear the current order
            unset($this->_SupplierProduct[$DD->id]);
            unset($this->_UserBox[$DD->id]);
            
            //Repopulate the order
            foreach($SPs as $id => $qty) 
            {    
                $Product = SupplierProduct::model()->findByPk($id);
                $availTo = strtotime($Product->customer_available_to);
                $availFrom = strtotime($Product->customer_available_from);
                $curDate = strtotime($DD->date);
                if($curDate < $availTo && $curDate > $availFrom) {
                    $this->_SupplierProduct[$DD->id][$id] = $qty;
                } else {
                    $allOk = false;
                }
            }
            foreach($UBs as $id => $qty) 
            {
                //Find the equivalent box type for the current date
                $CopyBox = Box::model()->findByAttributes(array(
                    'box_id' => $id,
                ));
                $NewBox = Box::model()->findByAttributes(array(
                    'size_id' => $CopyBox->size_id,
                    'delivery_date_id' => $DD->id,
                ));
                if($NewBox) {
                    $this->_UserBox[$DD->id][$NewBox->box_id] = $qty;
                } else {
                    Yii::app()->user->setFlash('warning','A box has been removed from your future orders.');
                }
            }
        }

        $this->_user->setState('boxocart.SupplierProduct', $this->_SupplierProduct);
        $this->_user->setState('boxocart.UserBox', $this->_UserBox);
        
        return $allOk;
    }

    public function getNextTotal($days=null)
    {
        $NextDate = $this->Location->getNextDeliveryDate();
        
        //Work out how many days difference from today and the next order
        $today = date('Y-m-d');
        $diff = (strtotime($NextDate->date) - strtotime($today)) / (60*60*24);
        $days -= $diff;
        
        $total = 0;
        if($days)
        {
            $DDs = $this->Location->getFutureDeliveryDates($NextDate,$days,null,'DAY');
            foreach($DDs as $DD) {
                $total += $this->getTotal($DD->id);
            }
        } else {
            $total += $this->getTotal($NextDate->id);
        }
        
        if($this->_user) {
            $total -= $this->Customer->balance;
            if($total < 0)
                $total = 0;
        }
        
        return $total;
    }
    
    public function getAllTotal()
    {
        $DDs = $this->getDeliveryDates();
        
        $total = 0;
        foreach($DDs as $ddId) {
            $total += $this->getTotal($ddId->id);
        }
        
        if($this->_user) {
            $total -= $this->Customer->balance;
            if($total < 0)
                $total = 0;
        }
        
        return $total;
    }
    
    public function removeOrder($id)
    {
        unset($this->_SupplierProduct[$id]);
        unset($this->_UserBox[$id]);
        $this->_user->setState('boxocart.SupplierProduct', $this->_SupplierProduct);
        $this->_user->setState('boxocart.UserBox', $this->_UserBox);
        return true;
    }
    
    public function revertChanges()
    {
        $this->_SupplierProduct = $this->_SupplierProduct_Before;
        $this->_UserBox = $this->_UserBox_Before;
        $this->_user->setState('boxocart.SupplierProduct', $this->_SupplierProduct);
        $this->_user->setState('boxocart.UserBox', $this->_UserBox);
        return true;
    }
    
    public function emptyCart()
    {
        $this->_SupplierProduct = array();
        $this->_UserBox = array();
        $this->_SupplierProduct_Before = array();
        $this->_UserBox_Before = array();
        $this->_user->setState('boxocart.SupplierProduct', $this->_SupplierProduct);
        $this->_user->setState('boxocart.UserBox', $this->_UserBox);
        $this->_user->setState('boxocart.SupplierProduct_Before', $this->_SupplierProduct_Before);
        $this->_user->setState('boxocart.UserBox_Before', $this->_UserBox_Before);
        return true;
    }
    
    public function confirmOrder()
    {
        $userId = $this->_user->id;
        $locationId = $this->_location_id;
        
        foreach($this->getDeliveryDates(true) as $DD) {
            $Order = $this->_getOrder($userId, $DD->id, $locationId);
            $Order->clearOrder();
        }
                
        foreach($this->_UserBox as $ddId => $UBs)
        {
            $this->delivery_date_id = $ddId;
            $Order = $this->_getOrder($userId, $ddId, $locationId);
            $Order->save();
   
            foreach($this->getUserBoxes('after') as $UB)
            {
                $UB->order_id = $Order->id;
                $UB->save();
            }
        }
        
        foreach($this->_SupplierProduct as $ddId => $SPs) 
        {
            $this->delivery_date_id = $ddId;
            $Order = $this->_getOrder($userId, $ddId, $locationId);
            if($Order->save()) 
            {
                foreach($this->getProducts('after') as $OrderItem) 
                { 
                    //give the customer the extra
                    $OrderItem->order_id = $Order->id;
                    $OrderItem->save();
                }
            }
            else
            {
                Yii::app()->user->setFlash('danger','<strong>Error.</strong> Could not save order. This usually happens when the order deadline has passed.');
            }
        }
        
        $this->emptyCart();
        $this->populateCart();
        $this->Customer->clearEmptyOrders();
        
        return true;
    }
    
    protected function _getOrder($userId, $ddId, $locationId)
    {
        //Create an order for this date if it doesn't already exist
        $Order = Order::model()->findByAttributes(array(
            'delivery_date_id' => $ddId,
            'user_id' => $userId,
        ));
        if (!$Order)
        {
            $Order = new Order;
            $Order->delivery_date_id = $ddId;
            $Order->user_id = $userId;
            $Order->location_id = $locationId;
            //$Order->save();
        }
        return $Order;
    }
    
    public function populateCart()
    {
        $Customer = $this->Customer;
        if($Customer) 
        {
            foreach($Customer->getFutureOrders() as $Order) 
            {
                foreach($Order->Extras as $OI) {
                    $this->_SupplierProduct[$Order->delivery_date_id][$OI->supplier_product_id] = $OI->quantity;
                    $this->_SupplierProduct_Before[$Order->delivery_date_id][$OI->supplier_product_id] = $OI->quantity;
                }
                
                foreach($Order->UserBoxes as $UB) {
                    //var_dump($UB->attributes);exit;
                    $this->_UserBox[$Order->delivery_date_id][$UB->box_id] = $UB->quantity;
                    $this->_UserBox_Before[$Order->delivery_date_id][$UB->box_id] = $UB->quantity;
                }
            }
        }
        
        $this->_user->setState('boxocart.SupplierProduct', $this->_SupplierProduct);
        $this->_user->setState('boxocart.UserBox', $this->_UserBox);
        $this->_user->setState('boxocart.SupplierProduct_Before', $this->_SupplierProduct_Before);
        $this->_user->setState('boxocart.UserBox_Before', $this->_UserBox_Before);
    }
    
    public function getDaysToLastDate()
    {
        $DDs = $this->getDeliveryDates();
        $lastDate = 0;
        foreach($DDs as $DD) {
            $curDate = strtotime($DD->date);
            if($curDate > $lastDate)
                $lastDate = $curDate;
        }
        $days = ($lastDate - time())/(60*60*24);
        return floor($days);
    }
    
    public function currentOrderExists()
    {
        $ddIds = array_keys($this->_SupplierProduct_Before);
        $ddIds = array_merge($ddIds, array_keys($this->_UserBox_Before));
        return in_array($this->delivery_date_id, $ddIds);
    }
    
    public function currentOrderRemoved()
    {
        $ddIds = array_keys($this->_SupplierProduct);
        $ddIds = array_merge($ddIds, array_keys($this->_UserBox));
        return !in_array($this->delivery_date_id, $ddIds);
    }
    
    public function productRemoved($prodId) 
    {
        return !isset($this->_SupplierProduct[$this->delivery_date_id][$prodId]);
    }
    
    public function productAdded($prodId) 
    {
        return isset($this->_SupplierProduct[$this->delivery_date_id][$prodId]) && !isset($this->_SupplierProduct_Before[$this->delivery_date_id][$prodId]);
    }
    
    public function boxRemoved($boxId) 
    {
        return !isset($this->_UserBox[$this->delivery_date_id][$boxId]);
    }
    
    public function boxAdded($boxId) 
    {
        return isset($this->_UserBox[$this->delivery_date_id][$boxId]) && !isset($this->_UserBox_Before[$this->delivery_date_id][$boxId]);
    }
    
    public function getTotalLabel($ddId)
    {
        $before = $this->getTotal($ddId, 'before');
        $after = $this->getTotal($ddId, 'after');
        
        $output = '';
        if(!$this->currentOrderRemoved()) {
            $output .= '<span class="current-price">'.SnapFormat::currency($after).'</span>';
        }
        
        if($before !== 0 && $after !== $before) {
            $output = ' <s class="text-danger">'.SnapFormat::currency($before).'</s>' . $output;
        }
        return $output;
    }
    
    public function productChanged($ddId, $OrderItem)
    {
        $prodId = $OrderItem->supplier_product_id;
        $before = false;
        $after = false;
        if(isset($this->_SupplierProduct_Before[$ddId][$prodId])) {
            $before = (float) $this->_SupplierProduct_Before[$ddId][$prodId];
        }
        if(isset($this->_SupplierProduct[$ddId][$prodId])) {
            $after = (float) $this->_SupplierProduct[$ddId][$prodId];
        }
        
        return $before !== false && $after !== false && $before != $after;
    }
    
    public function getProductBefore($ddId, $OrderItem)
    {
        $OI = false;
        $prodId = $OrderItem->supplier_product_id;
        if(isset($this->_SupplierProduct_Before[$ddId][$prodId])) 
        {
            $OI = new OrderItem;
            $OI->supplier_product_id = $prodId;
            $SP = $OI->SupplierProduct;
            $OI->quantity = $this->_SupplierProduct_Before[$ddId][$prodId];
            $OI->price = $SP->item_sales_price;
            $OI->name = $SP->name;
            $OI->unit = $SP->unit;
            $OI->packing_station_id = $SP->packing_station_id;
        }
        return $OI;
    }
    
    public function boxChanged($ddId, $UserBox)
    {
        $boxId = $UserBox->box_id;

        $before = false;
        $after = false;
        if(isset($this->_UserBox_Before[$ddId][$boxId])) {
            $before = (float) $this->_UserBox_Before[$ddId][$boxId];
        }
        if(isset($this->_UserBox[$ddId][$boxId])) {
            $after = (float) $this->_UserBox[$ddId][$boxId];
        }
        return $before !== false && $after !== false && $before != $after;
    }
    
    public function getBoxBefore($ddId, $UserBox)
    {
        $UB = false;
        $boxId = $UserBox->box_id;
        if(isset($this->_UserBox_Before[$ddId][$boxId])) 
        {
            $UB = new UserBox;
            $UB->quantity = $this->_UserBox_Before[$ddId][$boxId];
            $UB->box_id = $UserBox->box_id;
            $UB->price = $UB->Box->box_price;
            $UB->user_id = $this->_user->id;
        }
        return $UB;
    }
    
    public function getNextDeliveryDate()
    {
        return $this->Location->getNextDeliveryDate($this->delivery_day);
    }
    
    public function getLastDeliveryDate()
    {
        $dds = $this->getDeliveryDates();
        return array_pop($dds);
    }
    
    public function getOrderDate($days)
    {
        $minDays = SnapUtil::config('boxomatic/minimumAdvancePayment');
        $days -= 7; // minus 1 week
        $NextDate = $this->Location->getNextDeliveryDate($this->delivery_day, $days);
        return $NextDate ? SnapFormat::date($NextDate->date,'full') : 'Error!';
    }
}