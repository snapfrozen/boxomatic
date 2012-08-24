<?php

class SnapUtil extends CHtml
{
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
?>
