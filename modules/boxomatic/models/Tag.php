<?php

/**
 * This is the model class for table "tags".
 *
 * The followings are the available columns in table 'tags':
 * @property integer $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Customers[] $customers
 */
class Tag extends BoxomaticActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return $this->tablePrefix . 'tags';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('name', 'length', 'max' => 100),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name', 'safe', 'on' => 'search'),
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
            'Users' => array(self::MANY_MANY, 'BoxomaticUser', SnapUtil::config('boxomatic/tablePrefix') . 'user_tags(tag_id, user_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Name',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Tag the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * 
     */
    public static function getUsedTags($relation)
    {
        $data = self::model()->with($relation)->findAll('tag_id is not null');
        return CHtml::listData($data, 'id', 'name');
    }

    /**
     * 
     */
    public static function getList()
    {
        return CHtml::listData(self::model()->findAll(), 'id', 'name');
    }

    /**
     * 
     */
    public static function deleteUnusedTags()
    {
        $relations = array_keys(self::model()->relations());
        foreach ($relations as $relation)
        {
            $unused = self::model()->with($relation)->findAll('tag_id is null');
            foreach ($unused as $tag)
            {
                $tag->delete();
            }
        }
    }

}
