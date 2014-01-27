<?php

class SnapButtonColumn extends CButtonColumn
{
	public $template = '{view}{update}{delete}';
	public $buttons = array();
	
	/**
	 * Initializes the column.
	 * This method registers necessary client script for the button column.
	 */
	public function init()
	{
		$this->initDefaultButtons();

		foreach($this->buttons as $id=>$button)
		{
			if(strpos($this->template,'{'.$id.'}')===false)
				unset($this->buttons[$id]);
			elseif(isset($button['click']))
			{
				if(!isset($button['options']['class']))
					$this->buttons[$id]['options']['class']=$id;
				if(!($button['click'] instanceof CJavaScriptExpression))
					$this->buttons[$id]['click']=new CJavaScriptExpression($button['click']);
			}
		}

		$this->registerClientScript();
	}
	
	/**
	 * Initializes the default buttons (view, update and delete).
	 */
	protected function initDefaultButtons()
	{
		//if($this->viewButtonOptions===null)
			$this->viewButtonOptions=array('title'=>'View');
		//if($this->updateButtonOptions===null)
			$this->updateButtonOptions= array('title'=>'Edit');
		//if($this->deleteButtonOptions===null)
			$this->deleteButtonOptions=array('title'=>'Delete');
			
		if($this->viewButtonLabel===null)
			$this->viewButtonLabel=Yii::t('zii','<i class="fi fi-magnifying-glass"></i>');
		if($this->updateButtonLabel===null)
			$this->updateButtonLabel=Yii::t('zii','<i class="fi fi-page-edit"></i>');
		if($this->deleteButtonLabel===null)
			$this->deleteButtonLabel=Yii::t('zii','<i class="fi fi-x"></i>');
		if($this->deleteConfirmation===null)
			$this->deleteConfirmation=Yii::t('zii','Are you sure you want to delete this item?');

		foreach(array('view','update','delete') as $id)
		{
			$button=array(
				'label'=>$this->{$id.'ButtonLabel'},
				'url'=>$this->{$id.'ButtonUrl'},
				'options'=>$this->{$id.'ButtonOptions'},
			);
			if(isset($this->buttons[$id]))
				$this->buttons[$id]=array_merge($button,$this->buttons[$id]);
			else
				$this->buttons[$id]=$button;
		}

		if(!isset($this->buttons['delete']['click']))
		{
			if(is_string($this->deleteConfirmation))
				$confirmation="if(!confirm(".CJavaScript::encode($this->deleteConfirmation).")) return false;";
			else
				$confirmation='';

			if(Yii::app()->request->enableCsrfValidation)
			{
				$csrfTokenName = Yii::app()->request->csrfTokenName;
				$csrfToken = Yii::app()->request->csrfToken;
				$csrf = "\n\t\tdata:{ '$csrfTokenName':'$csrfToken' },";
			}
			else
				$csrf = '';

			if($this->afterDelete===null)
				$this->afterDelete='function(){}';

			$this->buttons['delete']['click']=<<<EOD
function() {
	$confirmation
	var th = this,
		afterDelete = $this->afterDelete;
	jQuery('#{$this->grid->id}').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#{$this->grid->id}').yiiGridView('update');
			afterDelete(th, true, data);
		},
		error: function(XHR) {
			return afterDelete(th, false, XHR);
		}
	});
	return false;
}
EOD;
		}
	}

}
?>
