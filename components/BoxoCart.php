<?php

class BoxoCart extends CComponent
{
    protected $_location_id = null;
    protected $_user = null;
    protected $_SupplierProduct = null;
    protected $_UserBox = null;
    
    public function __construct()
    {
        $this->_user = Yii::app()->user;
        $User = isset($this->_user->id) ? BoxomaticUser::model()->findByPk($this->_user->id) : false;
        
        $this->_location_id = $this->_user->getState('boxocart.location_id', $User ? $User->location_id : array());
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
            unset($this->{$fieldName}[$id]);
        } else if(isset($this->{$fieldName}[$id])) {
            $this->{$fieldName}[$id] = $qty;
        }
        $this->_user->setState('boxocart.'.$modelName, $this->$fieldName);
        return $this;
    }
    
    public function addItem($modelName, $id, $qty)
    {
        $fieldName = '_'.$modelName;
        if(isset($this->{$fieldName}[$id])) {
            $this->{$fieldName}[$id] += $qty;
        } else {
            $this->{$fieldName}[$id] = $qty;
        }
        
        //Remove if the new quantity is 0
        if($this->{$fieldName}[$id] <= 0) {
            unset($this->{$fieldName}[$id]);
        }
        
        $this->_user->setState('boxocart.'.$modelName, $this->$fieldName);
        return $this;
    }
    
    public function getProducts()
    {
        $order = array();
        foreach($this->_SupplierProduct as $id => $qty) 
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
        foreach($this->_UserBox as $id => $qty) 
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
    
    public function getTotal()
    {
        $total = 0;
        foreach($this->products as $OI) {
            $total += $OI->total;
        }
        foreach($this->userBoxes as $UB) {
            $total += $UB->total_price;
        }
        return $total;
    }
    
    public function getLocation()
    {
        return Location::model()->findByPk($this->_location_id);
    }
}