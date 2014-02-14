<?php
class SnapUtil
{
    public function createUrl($route, $params = array(), $ampersand = '&')
    {
        $route = preg_replace_callback('/(?<![A-Z])[A-Z]/', function($matches) {
            return '-' . lcfirst($matches[0]);
        }, $route);
        return parent::createUrl($route, $params, $ampersand);
    }
 
    public function parseUrl($request)
    {
		$path='/'.Yii::app()->request->pathInfo;
		$MI=MenuItem::model()->findByAttributes(array('path'=>$path));
		if($MI && $MI->content_id) {
			$route='content/view/id/'.$MI->content_id;
		} else {
			$route = parent::parseUrl($request);
		}
        return lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $route))));
    }
	
	/**
	 * Returns a config value found in modules/snapcms/config
	 * @param string $path Yii style path representation of a config file
	 * @return array the config array for the chosen file
	 */
	public static function getConfig($path)
	{
		$confPath = Yii::getPathOfAlias('application.modules.snapcms.config.'.$path);
		return require($confPath.'.php');
	}
	
	/**
	 * Returns a slated and hashed string
	 * @param string $password The password you wish to hash
	 * @return string A hashed and salted tstring
	 */
	public static function doHash($password)
	{
		$conf = SnapUtil::getConfig('general');
		$salt = $conf['security']['salt'];
		$hash = hash('sha256',$password.$salt);
		return $hash;
	}
	
	/**
	 * Create an associative array of a set of models
	 * @param array $models a list of model objects.
	 * @param mixed a comma separated string of fields or an array of fields
	 */
	public static function makeArray($models, $fields=null, $key=false)
	{
		$arrayData=array();
		
		if($fields && !is_array($fields))
		{
			$fields=explode(',',$fields);
		}
		
		if($fields===null)
		{
			foreach($models as $model)
			{
				if(!$key)
					$arrayData[$model->getPrimaryKey()]=$model->attributes;
				else
				{
					$keyValue=self::value($model,$key);
					$arrayData[$keyValue]=$model->attributes;
				}
			}
		}
		else
		{
			foreach($models as $model)
			{
				foreach($fields as $field)
				{
					$value=self::value($model,$field);
					if(!$key) {
						$keyValue=self::value($model,$key);
						$arrayData[$keyValue]=$model->attributes;
					} else {
						$arrayData[$model->getPrimaryKey()][$field]=$value;
					}
				}
			}
		}
		
		return $arrayData;
	}
}