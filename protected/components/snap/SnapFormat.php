<?php

class SnapFormat extends CApplicationComponent
{	
	/**
	 * Get month name from month number
	 * @param int month number
	 * @return string month name
	 */
	public function getMonthName($month)
	{
		return date("F", mktime(0, 0, 0, $month));
	}
	
	/**
	 * Make sure the url is a valid external hyperlink, e.g. contains http://
	 * @param type $url 
	 */
	public function createExternalUrl($url)
	{
		if(!strpos($url,'http://') || !strpos($url,'https://'))
			$url = 'http://' . $url;
		return $url;
	}
}
?>
