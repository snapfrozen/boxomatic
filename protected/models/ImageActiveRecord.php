<?php

class ImageActiveRecord extends CActiveRecord
{
	static $imageDir = 'data/images';
	static $defaultImageLocation = 'data/images/default.gif';
	
	static $phpThumbSizes = array(
		'tiny'=>array(
			'w'=>70,
			'h'=>70,
			'zc'=>1,
		),
		'small'=>array(
			'w'=>168,
			'h'=>168,
			'zc'=>1,
		),
		//large-4 column
		'medium'=>array(
			'w'=>279,
		),
	);
	
	/**
	 * 
	 * @param type $size
	 */
	public static function setPHPThumbParameters($size)
	{
		$_GET += self::$phpThumbSizes[$size];
	}

	/**
	 * 
	 */
	public function getImage_location()
	{
		$imageLoc='';
		if(!empty($this->image))
			$imageLoc = Yii::app()->basePath . '/'. static::$imageDir . '/' . $this->id . '.' . $this->image_ext;
		return $imageLoc;
	}
	
	/**
	 * 
	 */
	public function getImage_url()
	{
		$imageUrl='';
		if(!empty($this->image))
			$imageUrl = Yii::app()->baseUrl . static::$imageDir . '/' . $this->id . '' . $this->image_ext;
		return $imageUrl;
	}
	
	public function beforeSave()
	{
		if($this->image instanceof CUploadedFile) {
			$this->setAttribute('image_ext',$this->image->extensionName);
			$this->image->saveAs($this->image_location);
		}
		return parent::beforeSave();
	}
}