<?php

class SnapFormat extends CApplicationComponent
{	
	/**
	 * @param int month number
	 * @return string month name
	 */
	public function getMonthName($month)
	{
		return date("F", mktime(0, 0, 0, $month));
	}
}
?>
