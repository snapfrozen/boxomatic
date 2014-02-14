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
		return date("F", mktime(0, 0, 0, $month, 1));
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
	
//	public function date($day)
//	{
//		return Yii::app()->dateFormatter->format("yyy-M-dd", $day);
//	}
	
	/**
	 * 
	 * @param type $date
	 * @param string $dateWidth 'full', 'long', 'medium', 'short'
	 * @return type
	 */
	static function date($date,$dateWidth='medium',$timeWidth=null)
	{
		return Yii::app()->dateFormatter->formatDateTime($date,$dateWidth,$timeWidth);
	}
	
	//http://stackoverflow.com/questions/2955251/php-function-to-make-slug-url-string
	static public function slugify($text)
	{ 
		// replace non letter or digits by -
		
		$text = preg_replace('~[^\\pL\d\/]+~u', '-', $text);
		//echo $text.'<br />';
		// trim
		$text = trim($text, '-');
		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		// lowercase
		$text = strtolower($text);
		
		
		
		// remove unwanted characters
		$text = preg_replace('~[^-\w\/]+~', '', $text);
		if (empty($text))
		{
			return 'n-a';
		}
		return $text;
	}

}
