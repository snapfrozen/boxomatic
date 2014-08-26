<?php

class ImageActiveRecord extends BoxomaticActiveRecord
{
	static $imageDir = 'images';
	static $defaultImageLocation = 'images/default.gif';
	
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
		$base=Yii::getPathOfAlias('backend.data');
		$imageLoc='';
		if(!empty($this->image))
			$imageLoc = $base . '/'. static::$imageDir . '/' . $this->id . '.' . $this->image_ext;
		
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
		$dataDir = Yii::getPathOfAlias('backend.data');
		
		$field='image';
		$uploadFile=CUploadedFile::getInstance($this,$field);
		
		if(!$uploadFile) 
			return parent::beforeSave();

		$this->$field=$uploadFile;
		$dirPath=$dataDir.'/'.strtolower(__CLASS__);
		if (!file_exists($dirPath)) {
			mkdir($dirPath, 0777, true);
		}

		if(!$this->$field || !$this->$field->saveAs($dirPath.'/'.$field.'_'.$this->id))
			Yii::app()->user->setFlash('danger','problem saving image for field: '.$field);
		
		return parent::beforeSave();
	}
}