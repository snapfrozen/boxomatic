<?php $this->beginContent('//layouts/main'); ?>
<div class="row">
	<div class="large-9 columns">
		<?php echo $content; ?>
	</div>
	<div class="large-3 columns">
		<?php
		if(isset($this->operations)):
			$this->beginWidget('zii.widgets.CPortlet', array(
				'title'=>'Operations',
				'titleCssClass' => 'panel-title',
				'decorationCssClass' => 'panel-heading',
				'htmlOptions'=>array('class'=>'panel panel-success')
			));
			$this->widget('zii.widgets.CMenu', array(
				'items'=>$this->operations,
				'htmlOptions'=>array('class'=>'nav'),
			));
			$this->endWidget();
		endif;
		?>
		<?php
		if(isset($this->getMenuArray)):
			$this->beginWidget('zii.widgets.CPortlet', array(
				'title'=>'Menu',
				'titleCssClass' => 'panel-title',
				'decorationCssClass' => 'panel-heading',
				'htmlOptions'=>array('class'=>'panel panel-info')
			));
			$this->widget('zii.widgets.CMenu', array(
				'items'=>$this->getMenuArray(),
				'htmlOptions'=>array('class'=>'nav'),
			));
			$this->endWidget();
		endif;
		?>
		<?php
			$this->beginWidget('zii.widgets.CPortlet', array(
				'title'=>'Menu',
				'titleCssClass' => 'panel-title',
				'decorationCssClass' => 'panel-heading',
				'htmlOptions'=>array('class'=>'panel panel-info')
			));
			$this->widget('zii.widgets.CMenu', array(
				'items'=>$this->menu,
				'htmlOptions'=>array('class'=>'nav'),
			));
			$this->endWidget();
		?>
	</div>
</div>
<?php $this->endContent(); ?>