<?php
/* @var $this ContentController */
/* @var $model Content */
/* @var $form CActiveForm */
$menus = Menu::getMenus();
?>

<div class="form">

<?php $form=$this->beginWidget('SnapActiveForm', array(
	'id'=>'content-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('class'=>'col-md-12','enctype' => 'multipart/form-data'),
)); ?>
	
	<div class="row <?php echo $model->hasErrors('title') ? 'has-error' : ''; ?>">
		<div class="large-12 columns">
			<?php echo $form->labelEx($model,'title'); ?>
			<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
			<?php echo $form->error($model,'title',array('class'=>'help-block')); ?>
		</div>
	</div>
	
	<div class="row checkbox <?php echo $model->hasErrors('published') ? 'has-error' : ''; ?>">
		<div class="large-12 columns">
			<?php echo $form->checkBox($model,'published'); ?>
			<?php echo $form->labelEx($model,'published'); ?>
			<?php echo $form->error($model,'published',array('class'=>'help-block')); ?>
		</div>
	</div>

	<?php if(isset($model->ContentType)): ?>
		<?php foreach($model->ContentType->attributes as $field=>$attrib): ?>
		<div class="row form-group <?php echo $model->hasErrors('type') ? 'has-error' : ''; ?>">
			<div class="large-12 columns">
				<?php echo $form->labelEx($model,$field); ?>
				<?php echo $form->autoGenerateInput($model->ContentType, $field); ?>
			</div>
		</div>
		<?php endforeach; ?>
	<?php endif; ?>
		
	<fieldset>
		<legend>Menu</legend>
		<div class="section-container" data-section>
			<?php foreach($menus as $pos=>$menu): 
				$MI=$model->getMenuItem($menu);
			?>
			<section class="section <?php echo $pos==0 ? 'active' : '' ?>">
				<p class="title" data-section-title><a href="#tab<?php echo $pos ?>"><?php echo $menu->name ?></a></p>
				<div class="content" data-section-content>
					<?php echo $this->renderPartial('_menu_item_form',array('model'=>$MI,'form'=>$form)); ?>
				</div>
			</section>
			<?php endforeach; ?>
		</div>
	</fieldset>

	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class'=>'button')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->