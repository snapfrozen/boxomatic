<?php

class BoxoCart extends CComponent
{
    protected $_location_id;
    protected $_delivery_date_id;
    protected $_user;
    protected $_SupplierProduct;
    protected $_UserBox;
    protected $_Orders;
    
    public function __construct()
    {
        $this->_user = Yii::app()->user;
        $User = isset($this->_user->id) ? BoxomaticUser::model()->findByPk($this->_user->id) : false;
        
        $this->_location_id = $this->_user->getState('boxocart.location_id', $User ? $User->location_id : null);
        $this->_delivery_date_id = $this->_user->getState('boxocart.delivery_date_id', $User ? $User->location_id : null);
        $this->_SupplierProduct = $this->_user->getState('boxocart.SupplierProduct', array());
        $this->_UserBox = $this->_user->getState('boxocart.UserBox', array());
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
        $this->_delivery_date_id = $id;
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
            unset($this->{$fieldName}[$this->_delivery_date_id][$id]);
        } else if(isset($this->{$fieldName}[$this->_delivery_date_id][$id])) {
            $this->{$fieldName}[$this->_delivery_date_id][$id] = $qty;
        }
        $this->_user->setState('boxocart.'.$modelName, $this->$fieldName);
        return $this;
    }
    
    public function addItem($modelName, $id, $qty)
    {
        $fieldName = '_'.$modelName;
        if(isset($this->{$fieldName}[$this->_delivery_date_id][$id])) {
            $this->{$fieldName}[$this->_delivery_date_id][$id] += $qty;
        } else {
            $this->{$fieldName}[$this->_delivery_date_id][$id] = $qty;
        }
        
        //Remove if the new quantity is 0
        if($this->{$fieldName}[$this->_delivery_date_id][$id] <= 0) {
            unset($this->{$fieldName}[$this->_delivery_date_id][$id]);
        }
        
        $this->_user->setState('boxocart.'.$modelName, $this->$fieldName);
        return $this;
    }
    
    public function getProducts()
    {
        $order = array();
        if(!isset($this->_SupplierProduct[$this->_delivery_date_id])) {
            return $order;
        }
        
        foreach($this->_SupplierProduct[$this->_delivery_date_id] as $id => $qty) 
        {
            $OI = new OrderItem;
            $OI->supplier_product_id = $id;
            $OI->quantity = $qty;
            $OI->price = $OI->SupplierProduct->item_sales_price;
            $order[] = $OI;
        }
        return $order;
    }
    
    public function getUserBoxes()
    {
        $order = array();
        if(!isset($this->_UserBox[$this->_delivery_date_id])) {
            return $order;
        }
        
        foreach($this->_UserBox[$this->_delivery_date_id] as $id => $qty) 
        {
            $UB = new UserBox;
            $UB->quantity = $qty;
            $UB->box_id = $id;
            $UB->price = $UB->Box->box_price;
            //$UB->location_id = $this->_location_id;
            $order[] = $UB;
        }
        return $order;
    }
    
    public function getTotal($dateId=null)
    {
        $curDate = $this->_delivery_date_id;
        if($dateId) {
            $this->_delivery_date_id = $dateId;
        }
        
        $total = 0;
        foreach($this->products as $OI) {
            $total += $OI->total;
        }
        foreach($this->userBoxes as $UB) {
            $total += $UB->total_price;
        }
        
        $this->_delivery_date_id = $curDate;
        return $total;
    }
    
    public function getLocation()
    {
        return Location::model()->findByPk($this->_location_id);
    }
    
    public function getDeliveryDate()
    {
        return DeliveryDate::model()->findByPk($this->_delivery_date_id);
    }
    
    /**
     * @param type $DDs Array of DeliveryDate objects
     */
    public function repeatCurrentOrder($DDs)
    {
        $SPs = isset($this->_SupplierProduct[$this->_delivery_date_id]) ? $this->_SupplierProduct[$this->_delivery_date_id] : array();
        $UBs = isset($this->_UserBox[$this->_delivery_date_id]) ? $this->_UserBox[$this->_delivery_date_id] : array();
        
        $allOk = true;
        foreach($DDs as $DD)
        {
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
            foreach($UBs as $id => $qty) {
                $this->_UserBox[$DD->id][$id] = $qty;
            }
        }

        $this->_user->setState('boxocart.SupplierProduct', $this->_SupplierProduct);
        $this->_user->setState('boxocart.UserBox', $this->_UserBox);
        
        return $allOk;
    }
    
    public function getDeliveryDates()
    {
        $ddIds = array_keys($this->_SupplierProduct);
        $ddIds = array_merge($ddIds, array_keys($this->_UserBox));
        $DDs = array();
        foreach($ddIds as $id) {
            $DDs[$id] = DeliveryDate::model()->findByPk($id);
        }
        return $DDs;
    }
    
    public function getNextTotal()
    {
        $nextId = $this->Location->getNextDeliveryDate()->id;
        return $this->getTotal($nextId);
    }
    
    public function getAllTotal($num=null)
    {
        $total = 0;
        $dds = $this->getDeliveryDates();
        
        if($num) {
            $dds = array_slice($dds, 0, $num);
        }
        
        foreach($dds as $ddId) {
            $total += $this->getTotal($ddId->id);
        }
        return $total;
    }
}