<?php

/**
 * CEditableGridView represents a grid view which contains editable rows
 * and an optional 'Quickbar' which fires an action that quickly adds
 * entries to the table.
 *
 * To make a Column editable you have to assign it to the class 'CEditableColumn'
 *
 * Use it like the CGridView:
 *
 * $this->widget('zii.widgets.grid.CEditableGridView', array(
 *     'dataProvider'=>$dataProvider,
 *     'showQuickBar'=>'true',
 *     'quickCreateAction'=>'QuickCreate', // will be actionQuickCreate()
 *     'columns'=>array(
 *           'title',          // display the 'title' attribute
 *            array('header' => 'editMe', 'name' => 'editable_row', 'class' => 'CEditableColumn')
 *     ));
 *
 * With this Config, the column "editable_row" gets rendered with
 * inputfields. The Table-header will be called "editMe".
 *
 * You have to define a action that receives $_POST data like this:
 *   public function actionQuickCreate() {
 *	   $model=new Model;
 *      if(isset($_POST['Model']))
 *       {
 * 	      $model->attributes=$_POST['Model'];
 * 	      if($model->save())
 * 	      $this->redirect(array('admin')); //<-- assuming the Grid was used unter view admin/
 *       }
 *     }
 *
 * @author Herbert Maschke <thyseus@gmail.com>
 * @package zii.widgets.grid
 * @since 1.1
 */

Yii::import('zii.widgets.grid.CGridView');

class CEditableGridView extends CGridView {
	public $showQuickBar=1;
	public $quickCreateAction='QuickCreate';
	public $quickUpdateAction='QuickUpdate';
	public $addButtonValue='+';
	public $addLabelValue='Insert';
	public $options=array();
	public $extUrl=null;

	public function renderQuickBar() {
		echo '<tr class="quickBar">';
		foreach($this->columns as $column) 
		{
			if($column instanceof CEditableColumn) 
			{
				$model=$this->dataProvider->model;
				if(strstr($column->name, '.') != FALSE) // Column contains an relation
				{
					$relation=explode('.', $column->name);
					$id=CHtml::activeId($model,$model->getActiveRelation($relation[0])->foreignKey);
					$inputID=$id.'_new';
					$widget=$this->widget('Relation', array(
						'model' => $this->dataProvider->modelClass, 
						'relation' => $relation[0] , 
						'fields' => $relation[1],
						'hideAddButton' => $column->hideRelatedAddButton,
						'htmlOptions' => array('id'=>$inputID)
					) ,true); 

					printf('<td><div class="egv-dropdown">%s', $widget);
					printf('<div style="display:none" id="%s_em_" class="message error"></div></div></td>', $inputID);
				} else {
					$id=CHtml::activeId($model,$column->name);
					$inputID=$id.'_new';
					$fieldType=$column->fieldType;
					$input=CHtml::$fieldType($model,$column->name,array('id'=>$inputID,'value'=>''));
					printf('<td><div>%s', $input);
					printf('<div style="display:none" id="%s_em_" class="message error"></div></div></td>', $inputID);
				}
			}
			else
				printf('<td></td>');
		}
		echo "</tr>";
	}
	
	/**
	 * Renders the data items for the grid view.
	 */
	public function renderItems()
	{
		
		if($this->dataProvider->getItemCount()>0 || $this->showTableOnEmpty)
		{
			printf('<form class="egv-form-add" method="post" action="index.php?r=%s/%s">',$this->dataProvider->modelClass,$this->quickCreateAction);
			echo "<table class=\"{$this->itemsCssClass}\">\n";
			$this->renderTableHeader();
			ob_start();
			$this->renderTableBody();
			$body=ob_get_clean();
			$this->renderTableFooter();
			echo $body; // TFOOT must appear before TBODY according to the standard.
			echo "</table>";
			if($this->showQuickBar)
				printf('<div class="row quick-create"><label for="quickAddSubmit">%s</label><input id="quickAddSubmit" type=submit value="%s"></div>', $this->addLabelValue, $this->addButtonValue);
			echo "</form>";
		}
		else
			$this->renderEmptyText();
	}
	
	public function init()
	{
		if(!isset($this->htmlOptions['class']))
			$this->htmlOptions['class']='grid-view editable-grid-view';
		
		parent::init();
		
		$this->extUrl = Yii::app()
			->getAssetManager()
			->publish(dirname(__FILE__), false, -1, YII_DEBUG);
		
		//Populate options required for AJAX yiiactiveform javascript
		$data=$this->dataProvider->getData();
		$n=count($data);
		if($n>0)
		{
			for($row=0;$row<$n;++$row) 
			{
				foreach($this->columns as $column)
				{
					if($column instanceof CEditableColumn)
					{
						if($column->disabled || !$column->evaluateExpression($column->editableIf,array('data'=>$data[$row],'row'=>$row)))
							continue;
						
						$model=$this->dataProvider->model;
						if(strstr($column->name, '.') != FALSE) // Column contains an relation
						{
							$relation=explode('.', $column->name);
							$id=CHtml::activeId($model,$model->getActiveRelation($relation[0])->foreignKey);
						} 
						else
						{	
							$id=CHtml::activeId($model,$column->name);
						}
						
						if(!isset($data[$row])) {
							var_dump($row);
							var_dump($column->name);
						}
						$key=$data[$row]->primaryKey;
						$inputID=$id.'_'.$key;
						$this->options['attributes'][]=array(
							'id'=>$inputID,
							'inputID'=>$inputID,
							'errorID'=>$inputID.'_em_',
							'model'=>$this->dataProvider->modelClass,
							'name'=>$column->name,
							'enableAjaxValidation'=>1,
						);
					}
				}
			}
		}
		if($this->showQuickBar)
		{
			foreach($this->columns as $column)
			{
				if($column instanceof CEditableColumn) 
				{
					$model=$this->dataProvider->model;
					if(strstr($column->name, '.') != FALSE) // Column contains an relation
					{
						$data=explode('.', $column->name);
						$id=CHtml::activeId($model,$model->getActiveRelation($data[0])->foreignKey);
					} 
					else
					{	
						$id=CHtml::activeId($model,$column->name);
					}
					$inputID=$id.'_new';
					$this->options['attributes'][]=array(
						'id'=>$inputID,
						'inputID'=>$inputID,
						'errorID'=>$inputID.'_em_',
						'model'=>$model,
						'name'=>$column->name,
						'enableAjaxValidation'=>0,
					);
				}
			}
		}

	}

	public function renderTableBody() {
		parent::renderTableBody();
		if($this->showQuickBar)
			$this->renderQuickBar();
		
		$options=CJSON::encode($this->options);
		echo '<div class="egv-options" style="display:none;">' . $options . '</div>';
	}
	
	/**
	 * Registers necessary client scripts.
	 */
	public function registerClientScript()
	{
		$controller=Yii::app()->getController();
		$id=$this->id;
		$cs=Yii::app()->clientScript;
		$this->options['summaryID']=$id.'_es_';
		$this->options['hideErrorMessage']=true;
		
		$activeFormOptions=CJavaScript::encode($this->options);
		$gridOptions=CJavaScript::encode(array(
			'updateUrl'=>$controller->createUrl($controller->getId() . '/' . $this->quickUpdateAction)
		));
		
		$cs->registerCoreScript('yiiactiveform');
		$cs->registerScriptFile($this->extUrl.'/js/editable-grid-view.js', CClientScript::POS_END);
		$cs->registerScript('editabe-grid-view-'.$id, 
"jQuery('#$id').yiiEditableGridView($gridOptions);
$('#$id').yiiactiveform($activeFormOptions);	
", CClientScript::POS_END);
		
		//$this->afterAjaxUpdate="setActiveForm";
		$cs->registerCssFile($this->extUrl.'/css/editable-grid-view.css');
		parent::registerClientScript();
	}
	
	/**
	 * 
	 * @param type $models
	 * @param type $attributes
	 * @param type $loadInput
	 * @return type 
	 */
	public static function validate($models, $attributes=null, $loadInput=true)
	{
		$result=array();
		if(!is_array($models))
			$models=array($models);
		foreach($models as $model)
		{
			if($loadInput && isset($_POST[get_class($model)]))
				$model->attributes=$_POST[get_class($model)];
			$model->validate($attributes);
			foreach($model->getErrors() as $attribute=>$errors) 
			{
				$key=!empty($model->primaryKey)?$model->primaryKey:'new';
				$result[CHtml::activeId($model,$attribute).'_'.$key]=$errors;
			}
		}
		return function_exists('json_encode') ? json_encode($result) : CJSON::encode($result);
	}

}
