<?php
/**
 * CEditableColumn class file.
 *
 * @author Herbert Maschke <thyseus@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2010 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

Yii::import('zii.widgets.grid.CGridColumn');

/**
 * CEditableColumn represents a grid view column that is editable.
 *
 * @author Herbert Maschke <thyseus@gmail.com>
 * @package zii.widgets.grid
 * @since 1.1
 */
class CEditableColumn extends CDataColumn
{
	/**
	 * @var string the attribute name of the data model. The corresponding attribute value will be rendered
	 * in each data cell. If {@link value} is specified, this property will be ignored
	 * unless the column needs to be sortable.
	 * @see value
	 * @see sortable
	 */
	public $name;
	/**
	 * @var string a PHP expression that will be evaluated for every data cell and whose result will be rendered
	 * as the content of the data cells. In this expression, the variable
	 * <code>$row</code> the row number (zero-based); <code>$data</code> the data model for the row;
	 * and <code>$this</code> the column object.
	 */
	public $value;
	public $sortable;
	
	public $fieldType='activeTextField';
	public $hideRelatedAddButton=false;
	public $editableIf='true';
	public $disabled=false;
	public $widgetClass=null;
	public $widgetOptions=null;

	/**
	 * Initializes the column.
	 */
	public function init()
	{
		parent::init();
		if($this->name===null && $this->value===null)
			throw new CException(Yii::t('zii','Either "name" or "value" must be specified for CEditableColumn.'));
	}

	/**
	 * Renders the filter cell content.
	 * This method will render the {@link filter} as is if it is a string.
	 * If {@link filter} is an array, it is assumed to be a list of options, and a dropdown selector will be rendered.
	 * Otherwise if {@link filter} is not false, a text field is rendered.
	 * @since 1.1.1
	 */
	protected function renderFilterCellContent()
	{
		if(is_string($this->filter))
			echo $this->filter;
		else if($this->filter!==false && $this->grid->filter!==null && $this->name!==null && strpos($this->name,'.')===false)
		{
			if(is_array($this->filter))
				echo CHtml::activeDropDownList($this->grid->filter, $this->name, $this->filter, array('id'=>false,'prompt'=>''));
			else if($this->filter===null)
				echo CHtml::activeTextField($this->grid->filter, $this->name, array('id'=>false));
		} 
		else if($this->filter!==false && $this->grid->filter!==null && $this->name!==null && strstr($this->name, '.') != false) // Column contains an relation
		{
			$relation=explode('.', $this->name);
			echo $this->grid->widget('Relation', array(
				'model' => $this->grid->filter, 
				'relation' => $relation[0] , 
				'fields' => $relation[1],
				'hideAddButton' => true,
			) ,true); 
		}
		else
			parent::renderFilterCellContent();
	}

	/**
	 * Renders the data cell content.
	 * @param integer the row number (zero-based)
	 * @param mixed the data associated with the row
	 */
	protected function renderDataCellContent($row,$data)
	{
		$field=$this->name;
		$model=$this->grid->dataProvider->model;
		$editable=!$this->disabled && $this->evaluateExpression($this->editableIf,array('data'=>$data,'row'=>$row));
		if(strstr($field, '.') != false) // Column contains an relation
		{
			$relation=explode('.', $field);
			$value=$data->$relation[1];
			if(!$editable)
			{
				if($data->$relation[0])
					printf($data->$relation[0]->$relation[1]);
			}
			else
			{
				$id=CHtml::activeId($model,$model->getActiveRelation($relation[0])->foreignKey);
				$inputID=$id.'_'.$data->primaryKey;
				$widget=$this->grid->widget('Relation', array(
					'model'=>$data, 
					'relation'=>$relation[0], 
					'fields' => $relation[1], 
					'hideAddButton' => $this->hideRelatedAddButton,
					'htmlOptions' => array('id'=>$inputID)
				),true); 
				printf('<div class="egv-dropdown">%s', $widget);
				printf('<div style="display:none" id="_em_" class="message error"></div></div>');
			}
		} 
		else 
		{
			$value=$data->$field;
			if(!$editable)
			{
				printf($value);
			}
			else
			{
				$id=CHtml::activeId($model,$field);
				$inputID=$id.'_'.$data->primaryKey;

				if($this->widgetClass)
				{
					
					$tmpOptions=array('model'=>$data,'name'=>$inputID);
					$options=array_merge($tmpOptions,$this->widgetOptions);
					$input=$this->grid->widget($this->widgetClass, $options ,true); 
				}
				else
				{
					$fieldType=ucfirst($this->fieldType);
					$input=CHtml::$fieldType($data,$field,array('id'=>$inputID));
					//$error=CHtml::error($data,$field,array('id'=>$inputID.'_em_'));
				}
				
				printf('<div>%s', $input);
				printf('<div style="display:none" id="%s_em_" class="message error"></div></div>',$inputID);
			}
		}
	}
}
