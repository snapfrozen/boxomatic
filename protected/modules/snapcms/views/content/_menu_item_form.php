<?php $cnf=SnapUtil::getConfig('general'); ?>
<?php 
	if(!$model->content_id) { //New record
		$menuSelected = $model->Menu->name == $cnf['site']['defaultMenu']; 
	} else {
		$menuSelected = !$model->isNewRecord;
	}
?>

<?php echo $form->hiddenField($model,'menu_id',array('name'=>'MenuItem['.$model->Menu->name.'][menu_id]')); ?>
<?php echo $form->hiddenField($model,'id',array('name'=>'MenuItem['.$model->Menu->name.'][id]')); ?>

<div class="row checkbox <?php echo $model->hasErrors('parent') ? 'has-error' : ''; ?>">
	<div class="large-12 columns">
		<?php echo CHtml::checkbox('MenuItem['.$model->Menu->name.'][include]',$menuSelected,array('id'=>'menu-'.$model->id)); ?>
		<?php echo CHtml::label('Include in this menu','menu-'.$model->id); ?>
	</div>
</div>

<div class="row <?php echo $model->hasErrors('parent') ? 'has-error' : ''; ?>">
	<div class="large-12 columns">
		<?php echo $form->labelEx($model,'parent'); ?>
		<?php echo $form->dropDownList($model,'parent',$model->Menu->getItemDropDownList(),array('name'=>'MenuItem['.$model->Menu->name.'][parent]')); ?>
		<?php echo $form->error($model,'parent',array('class'=>'help-block')); ?>
	</div>
</div>

<div class="row <?php echo $model->hasErrors('path') ? 'has-error' : ''; ?>">
	<div class="large-12 columns">
		<?php echo $form->labelEx($model,'path'); ?>
		<?php echo $form->textField($model,'path',array('size'=>60,'maxlength'=>255,'name'=>'MenuItem['.$model->Menu->name.'][path]')); ?>
		<?php echo $form->error($model,'path',array('class'=>'help-block')); ?>
		<p class="help-block">e.g. /news/my-news-item<br />If nothing is entered this will automatically be set</p>
	</div>
</div>

<div class="row <?php echo $model->hasErrors('title') ? 'has-error' : ''; ?>">
	<div class="large-12 columns">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255,'name'=>'MenuItem['.$model->Menu->name.'][title]')); ?>
		<?php echo $form->error($model,'title',array('class'=>'help-block')); ?>
		<p class="help-block">If nothing is entered the page title will be used</p>
	</div>
</div>