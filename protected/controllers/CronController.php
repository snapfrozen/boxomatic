<?php

class CronController extends Controller
{

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Generate new weeks and boxes for each week
	 */
	public function actionCreateFutureWeeksAndBoxes()
	{
		$weeksInAdvance=Yii::app()->params['autoCreateWeeks'];
		
		$latestWeek=Week::getLastEnteredWeek();
		$latestDate=strtotime($latestWeek->week_delivery_date);
		$targetDate=strtotime('+' . $weeksInAdvance . ' weeks');

		$BoxSizes=BoxSize::model()->findAll();
		
		while($latestDate <= $targetDate) 
		{
			$latestDateStr=date('Y-m-d',$latestDate);
			$latestDate=strtotime($latestDateStr . ' +1 week');
			$newDateStr=date('Y-m-d', $latestDate);			
			
			$Week=new Week;
			$Week->week_delivery_date=$newDateStr;
			$Week->save();
			
			foreach($BoxSizes as $BoxSize)
			{
				$Box=new Box;
				$Box->size_id=$BoxSize->box_size_id;
				$Box->box_price=$BoxSize->box_size_price;
				$Box->week_id=$Week->week_id;
				$Box->save();
			}
			
			echo '<p>Created new week: ' . $Week->week_delivery_date . '</p>';
		}
		
		echo '<p><strong>Finished.</strong></p>';
		
		Yii::app()->end();
	}
	
}