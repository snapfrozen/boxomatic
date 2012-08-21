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
	 * @param string $url 
	 */
	public function createExternalUrl($url)
	{
		if(!strpos($url,'http://') || !strpos($url,'https://'))
			$url = 'http://' . $url;
		return $url;
	}
	
	/**
	 * Format a week string
	 * @param string $day
	 */
	public function dayOfYear($day)
	{
		return Yii::app()->dateFormatter->format("EEE MMM d, yyy", $day);
	}
	
	/**
	 * Format a week string
	 * @param double $url 
	 */
	public function currency($amount)
	{
		return Yii::app()->numberFormatter->format('$#,##0.00', (float)$amount);
	}
}
?>
