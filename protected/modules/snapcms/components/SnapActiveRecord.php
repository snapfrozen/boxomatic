<?php

/**
 * This is the base model / active record class 
 * all model classes MUST extend this class.
 * 
 * @author Andrew Mclagan
 */

abstract class SnapActiveRecord extends CActiveRecord {
	
	/**
	 * Prepare logging fields such as: created, updated, created_user_id, updated_user_id
	 * done before performing validation 
	 */
	protected function beforeValidate() 
	{
		if( $this->isNewRecord ) 
		{
			// is a new record
			if( self::hasAttribute('created') )
				$this->created = new CDbExpression('NOW()');
			if( self::hasAttribute('updated') )
				$this->updated = new CDbExpression('NOW()');
			if( self::hasAttribute('user_id') )
				$this->user_id = Yii::app()->user->id;
			if( self::hasAttribute('updated_user_id') )
				$this->updated_user_id = Yii::app()->user->id;
		}
		else {
			// we are updating an existing one
			if( self::hasAttribute('updated') )
				$this->updated = new CDbExpression('NOW()');
			if( self::hasAttribute('updated_player_id') )
				$this->updated_player_id = Yii::app()->user->id;			
		}
		
		return parent::beforeValidate();
	}
	
	/*
	public function behaviors()
	{
		return array(
			'activerecord-relation'=>array(
				'class'=>'ext.activerecord-relation.EActiveRecordRelationBehavior',
			)
		);
	}
	 */
	
	/**
	 * Check if this model belongs to the the current user 
	 */	
	public function getBelongs_to_user()
	{
		$user = Yii::app()->user;
		return !$user->isGuest && $user->id == $this->user_id;
	}
}