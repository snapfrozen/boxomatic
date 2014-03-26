<?php $form=$this->beginWidget('application.widgets.SnapActiveForm', array(
	'id'=>'supplier-search',
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'layout' => BsHtml::FORM_LAYOUT_HORIZONTAL,
	'htmlOptions' => array('class'=>'row'),
)); ?>
	<div class="col-lg-9 clearfix">
		<?php echo $form->textFieldControlGroup($model,'name'); ?>
		<?php echo $form->textFieldControlGroup($model,'item_search'); ?>
		
		<button class="btn btn-primary pull-right" name="yt0" type="submit">
			<span class="glyphicon glyphicon-search"></span> Search
		</button>
		
	</div>

<?php $this->endWidget(); ?>