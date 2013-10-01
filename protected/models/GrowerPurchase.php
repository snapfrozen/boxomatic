<?php

/**
 * This is the model class for table "grower_purchases".
 *
 * The followings are the available columns in table 'grower_purchases':
 * @property integer $grower_purchases_id
 * @property integer $grower_item_id
 * @property string $propsed_quantity
 * @property string $propsed_price
 * @property string $proposed_delivery_date
 * @property string $order_notes
 * @property string $delivered_quantity
 * @property string $final_price
 * @property string $delivery_nots
 *
 * The followings are the available model relations:
 * @property Growers $growerItem
 */
class GrowerPurchase extends CActiveRecord
{
	public $grower_cert_search;
	public $grower_name_search;
	public $item_name_search;
	public $total; //For aggregate SQL
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return GrowerPurchase the static model class
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
		return 'grower_purchases';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('grower_item_id', 'numerical', 'integerOnly'=>true),
			array('propsed_quantity, propsed_price, delivered_quantity, final_price', 'length', 'max'=>7),
			array('proposed_delivery_date, order_notes, delivery_nots', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('grower_name_search, item_name_search, grower_cert_search, grower_purchases_id, grower_item_id, propsed_quantity, propsed_price, proposed_delivery_date, order_notes, delivered_quantity, final_price, delivery_nots', 'safe', 'on'=>'search'),
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
			'growerItem' => array(self::BELONGS_TO, 'GrowerItem', 'grower_item_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'grower_purchases_id' => 'Order ID',
			'grower_item_id' => 'Grower Item',
			'propsed_quantity' => 'Propsed Quantity',
			'propsed_price' => 'Propsed Price',
			'proposed_delivery_date' => 'Proposed Delivery Date',
			'order_notes' => 'Order Notes',
			'delivered_quantity' => 'Delivered Quantity',
			'final_price' => 'Final Price',
			'delivery_nots' => 'Delivery Nots',
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

		$criteria->compare('grower_purchases_id',$this->grower_purchases_id);
		$criteria->compare('grower_item_id',$this->grower_item_id);
		$criteria->compare('propsed_quantity',$this->propsed_quantity,true);
		$criteria->compare('propsed_price',$this->propsed_price,true);
		$criteria->compare('proposed_delivery_date',$this->proposed_delivery_date,true);
		$criteria->compare('order_notes',$this->order_notes,true);
		$criteria->compare('delivered_quantity',$this->delivered_quantity,true);
		$criteria->compare('final_price',$this->final_price,true);
		$criteria->compare('delivery_nots',$this->delivery_nots,true);
		
		$criteria->compare('grower_certification_status',$this->grower_cert_search,true);
		$criteria->compare('grower_name',$this->grower_name_search,true);
		$criteria->compare('item_name',$this->item_name_search,true);
		$criteria->with = array('growerItem'=>array('with'=>'Grower'));

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>array(
					'proposed_delivery_date'=>true	
			))
		));
	}
	
	public function getProposed_delivery_date_formatted()
	{
		return Yii::app()->snapFormat->date($this->proposed_delivery_date);
	}
}