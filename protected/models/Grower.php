<?php

/**
 * This is the model class for table "growers".
 *
 * The followings are the available columns in table 'growers':
 * @property integer $grower_id
 * @property string $grower_name
 * @property string $grower_mobile
 * @property string $grower_phone
 * @property string $grower_address
 * @property string $grower_address2
 * @property string $grower_suburb
 * @property string $grower_state
 * @property string $grower_postcode
 * @property string $grower_distance_kms
 * @property string $grower_bank_account_name
 * @property string $grower_bank_bsb
 * @property string $grower_bank_acc
 * @property string $grower_email
 * @property string $grower_website
 * @property string $grower_certification_status
 * @property string $grower_order_days
 * @property string $grower_produce
 * @property string $grower_notes
 * @property string $grower_payment_details
 *
 * The followings are the available model relations:
 * @property GrowerItems[] $growerItems
 */
class Grower extends CActiveRecord
{
	public $grower_item_search;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Grower the static model class
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
		return 'growers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
//			array('grower_state', 'required'),
			array('grower_name, grower_mobile, grower_address2, grower_suburb, grower_postcode, grower_distance_kms, grower_bank_account_name, grower_bank_bsb, grower_bank_acc', 'length', 'max'=>45),
			array('grower_phone, grower_address, grower_website, grower_certification_status', 'length', 'max'=>150),
			array('grower_state', 'length', 'max'=>50),
			array('grower_email', 'length', 'max'=>100),
			array('grower_order_days, grower_certification_status, grower_website, grower_order_days', 'length', 'max'=>255),
			array('grower_produce, grower_notes, grower_payment_details', 'length', 'max'=>500),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('grower_item_search, grower_id, grower_name, grower_mobile, grower_phone, grower_address, grower_address2, grower_suburb, grower_state, grower_postcode, grower_distance_kms, grower_bank_account_name, grower_bank_bsb, grower_bank_acc, grower_email, grower_website, grower_certification_status, grower_order_days, grower_produce, grower_notes, grower_payment_details', 'safe', 'on'=>'search'),
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
			'GrowerItems' => array(self::HAS_MANY, 'GrowerItem', 'grower_id'),
			'BoxItems' => array(self::HAS_MANY , 'BoxItem', 'box_item_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'grower_id' => 'Grower',
			'grower_name' => 'Grower Name',
			'grower_mobile' => 'Mobile',
			'grower_phone' => 'Phone',
			'grower_address' => 'Address',
			'grower_address2' => '',
			'grower_suburb' => 'Suburb',
			'grower_state' => 'State',
			'grower_postcode' => 'Postcode',
			'grower_distance_kms' => 'Distance(km)',
			'grower_bank_account_name' => 'Bank Account Name',
			'grower_bank_bsb' => 'Bank BSB',
			'grower_bank_acc' => 'Bank Acc',
			'grower_email' => 'Email',
			'grower_website' => 'Website',
			'grower_certification_status' => 'Certification Status',
			'grower_order_days' => 'Order Days',
			'grower_produce' => 'Produce',
			'grower_notes' => 'Notes',
			'grower_payment_details' => 'Payment Details',
			'grower_item_search' => 'Produce',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($paginate=true)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		if(!empty($this->grower_item_search)) {
			$criteria->with=array('GrowerItems');
			$searchItems=explode(',',$this->grower_item_search);
			
			foreach($searchItems as $searchItem) {
				$criteria->addCondition('item_name LIKE "%' . $searchItem . '%"','OR');
			}
		}
	
		$criteria->compare('grower_id',$this->grower_id);
		$criteria->compare('grower_name',$this->grower_name,true);
		$criteria->compare('grower_mobile',$this->grower_mobile,true);
		$criteria->compare('grower_phone',$this->grower_phone,true);
		$criteria->compare('grower_address',$this->grower_address,true);
		$criteria->compare('grower_address2',$this->grower_address2,true);
		$criteria->compare('grower_suburb',$this->grower_suburb,true);
		$criteria->compare('grower_state',$this->grower_state,true);
		$criteria->compare('grower_postcode',$this->grower_postcode,true);
		$criteria->compare('grower_distance_kms',$this->grower_distance_kms,true);
		$criteria->compare('grower_bank_account_name',$this->grower_bank_account_name,true);
		$criteria->compare('grower_bank_bsb',$this->grower_bank_bsb,true);
		$criteria->compare('grower_bank_acc',$this->grower_bank_acc,true);
		$criteria->compare('grower_email',$this->grower_email,true);
		$criteria->compare('grower_website',$this->grower_website,true);
		$criteria->compare('grower_certification_status',$this->grower_certification_status,true);
		$criteria->compare('grower_order_days',$this->grower_order_days,true);
		$criteria->compare('grower_produce',$this->grower_produce,true);
		$criteria->compare('grower_notes',$this->grower_notes,true);
		$criteria->compare('grower_payment_details',$this->grower_payment_details,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=> $paginate ? array('pageSize'=>10) : $paginate,
		));
	}
}