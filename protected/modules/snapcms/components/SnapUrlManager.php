<?php
class SnapUrlManager extends CUrlManager
{
	//public $showScriptName = false;
    //public $appendParams = false;
	//public $useStrictParsing = true;
    //public $urlSuffix = '/';

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
}