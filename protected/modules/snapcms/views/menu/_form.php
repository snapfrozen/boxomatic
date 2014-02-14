<?php
/* @var $this MenuController */
/* @var $model Menu */
/* @var $form CActiveForm */
Yii::app()->getClientScript()->registerCoreScript( 'jquery.ui' );
?>

<div class="form row">

<?php $form=$this->beginWidget('SnapActiveForm', array(
	'id'=>'menu-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('class'=>'col-md-12'),
)); ?>
	
	<?php echo $form->errorSummary($model); ?>

	<?php
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>$model->name,
			'titleCssClass' => 'panel-title',
			'decorationCssClass' => 'panel-heading',
			'htmlOptions'=>array('class'=>'panel panel-info sortable')
		));
		$this->widget('zii.widgets.CMenu', array(
			'encodeLabel'=>false,
			'items'=>$model->getMenuList(array('admin'=>true)),
			'htmlOptions'=>array('class'=>'nav nav-stacked admin-nav'),
		));
		$this->endWidget();
	?>

	<div class="buttons">
		<?php echo CHtml::submitButton('Save', array('class'=>'btn btn-primary btn-lg')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->